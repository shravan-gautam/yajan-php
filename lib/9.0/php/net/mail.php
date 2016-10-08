<?php
@require_once "Mail.php";
@require_once "Mail/mime.php";
class SMTPMail
{
	var $from;
	var $to;
	var $smtpUser;
	var $smtpPass;
	var $smtpServer;
	var $smtpPort;
	var $message;
	var $html;
	var $attachement;
	var $imagelist;
	function SMTPMail($user="",$pass="",$server="",$port="")
	{
		global $SMTP_SERVER,$SMTP_USER,$SMTP_PASSWORD,$SMTP_PORT;
		if($user!="")
		{
			$this->smtpUser = $user;
		}
		else
		{
			$this->smtpUser = $SMTP_USER;
		}
		if($pass!="")
		{
			$this->smtpPass = $pass;
		}
		else
		{
			$this->smtpPass = $SMTP_PASSWORD;
		}
		if($server!="")
		{
			$this->smtpServer=$server;
		}
		else
		{
			$this->smtpServer=$SMTP_SERVER;
		}
		if($port!="")
		{
			$this->smtpPort=$port;
		}
		else
		{
			$this->smtpPort=$SMTP_PORT;
		}
		$this->imagelist = array();
		$this->attachement = array();
		$this->html=false;
	}
	function htmlFormat($val)
	{
		$this->html=$val;
	}
	function clearImages()
	{
		$this->imagelist = array();
	}
	function clearAttachement()
	{
		$this->attachement=array();
	}
	function embedImage($filename)
	{
		$i = count($this->imagelist);
		if(file_exists($filename))
		{
			$this->imagelist[$i]=$filename;
			return $i;
		}
		else
		{
			return false;
		}
	}
	function addAttachement($filename,$mime="application/octet-stream")
	{
		$i = count($this->attachement);
		if(file_exists($filename))
		{
			$this->attachement[$i]=array("name"=>$filename,"mime"=>$mime);
			return $i;
		}
		else
		{
			return false;
		}
	}
	function send($from,$to,$subject,$message)
	{
	
		$host = $this->smtpServer;
		$port = $this->smtpPort;
		$username = $this->smtpUser;
		$password = $this->smtpPass;
		$mime = new Mail_mime();
		$headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
		if(count($this->attachement)>0)
		{
			for($i=0;$i<count($this->attachement);$i++)
			{
				$att = $this->attachement[$i];
				$mime->addAttachment($att['name'],$att['mime']);
			}
		}
		if(count($this->imagelist)>0)
		{
			for($i=0;$i<count($this->imagelist);$i++)
			{
				$att = $this->imagelist[$i];
				$mime->addHTMLimage($att);
			}
		}
				
		if($this->html==true)
		{
			$mime->setHTMLBody($message);
			$mimeparams=array(); 
			$mimeparams['text_encoding']="8bit";
			$mimeparams['text_charset']="UTF-8";
			$mimeparams['html_charset']="UTF-8";
			$mimeparams['head_charset']="UTF-8";
			$body = $mime->get($mimeparams);	

			$headers["Content-Type"] = 'text/html; charset=UTF-8';
			$headers["Content-Transfer-Encoding"]= "8bit";
		}
		else
		{
			$mime->setTXTBody($message);
			$body = $mime->get();
		}
		$headers = $mime->headers($headers);
		
		$smtp = @Mail::factory('smtp',array ('host' => $host,'port' => $port,'auth' => true,'username' => $username,'password' => $password));
		$mail = $smtp->send($to, $headers, $body);

		if (PEAR::isError($mail))
		{
			$this->message = $mail->getMessage();
			return false;
		} 
		else 
		{
			return true;
		}
		
	}
}
?>