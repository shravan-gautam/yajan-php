<?php
import("net");
class SmsGupshap
{
	private $user;
	private $password;
	private $url;
	private $messageType;
	private $dnd;
	private $logfile;
	var $message;
	function __construct()
	{
		$this->url = 'http://enterprise.smsgupshup.com/GatewayAPI/rest';
		$this->messageType = "TEXT";
		$this->dnd="FALSE";
		$this->logfile=null;
	}
	function setSmsAccount($user,$pass)
	{
		$this->user = $user;
		$this->password = $pass;
	}
	function logpath($filename)
	{
		$this->logfile=new Logfile("sms",$filename,true);
	}
	function utf8($val)
	{
		if($val)
		{
			$this->messageType="Unicode_Text";
		}
	}
	function dnd($val)
	{
		if($val)
		{
			$this->dnd="TRUE";
		}
		else
		{
			$this->dnd="FALSE";
		}
	}
	function _send($to,$message,$mathod)
	{
		//$url = $this->url.='?method='.$mathod.'&send_to='.$to.'&msg='.$msg.'&msg_type='.$this->messageType.'&userid='.$this->user.'&auth_scheme=plain&password='.$this->password.'&v=1.1&format=text&override_dnd='.$this->dnd;
		$hr = new HttpRequest($this->url);
		$hr->addParameter("method",$mathod);
		$hr->addParameter("send_to",$to);
		$hr->addParameter("msg",urlencode($message));
		$hr->addParameter("msg_type",$this->messageType);
		$hr->addParameter("userid",$this->user);
		$hr->addParameter("auth_scheme","plain");
		$hr->addParameter("password",$this->password);
		$hr->addParameter("v","1.1");
		$hr->addParameter("format","text");
		$hr->addParameter("override_dnd","$this->dnd");
		$hr->mathod("get");
		
		$resp = $hr->send();
		if($this->logfile!=null)
		{
			$this->logfile->write("$to | $message | $resp");
		}
		return $resp;

	}
	function send($to,$message)
	{
		$val = $this->_send($to,$message,"sendMessage");
		$this->message = $val;
		$val = explode("|",$val);
		if(trim($val[0])=="error")
		{
			return false;
		}
		else
		{
			return true;
		}
		/*
		return $val;
		$val = explode("|",$val);
		$dx = new DataBox("smsresp");
		$dx->add("status",trim($val[0]));
		$dx->add("code",trim($val[1]));
		$dx->add("text",trim($val[2]));
		return $dx;
		*/
	}
}

?>