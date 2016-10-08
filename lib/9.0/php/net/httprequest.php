<?php
class HttpRequest
{
	var $url;
	var $timeout;
	var $link;
	var $parm;
	var $mathod;
	var $files;
	var $isolation;
	var $isolationKey;
	function HttpRequest($url="")
	{

		$this->timeout=5;
		$this->isolation=false;
		$this->isolationKey="";
		$this->link =  curl_init();
		$this->parm = array();
		$this->mathod="post";
		$this->files = array();
		if($url!="")
		{
			$this->url = $url;
			curl_setopt($this->link, CURLOPT_URL, $this->url);
			curl_setopt($this->link, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->link, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			
		}
	}
	function secretIsolation($val)
	{
		$this->isolation=$val;
	}
	function isolationKey($key)
	{
		$this->isolationKey=$key;
	}
	function getError()
	{
		
		if(curl_errno($this->link))
		{
			return curl_error($this->link);
		}
		return null;
	}
	function addFile($name,$filepath)
	{
		if(file_exists($filepath))
		{
			$this->parm[$name]='@'.$filepath;
			return true;
		}
		else
		{
			return false;
		}
	}
	function responceHeader($val)
	{
		curl_setopt($this->link,CURLOPT_HEADER, $val);
	}
	function setSourceIp($ip)
	{
		
		curl_setopt($this->link,CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "X_FORWARDED_FOR: $ip"));
		//curl_setopt($this->link,CURLOPT_HTTPHEADER, "X-Forwarded-For: {$ip}, {$ip}");
	}
	function redirect($val)
	{
		curl_setopt($this->link,CURLOPT_FOLLOWLOCATION, $val);
		curl_setopt($this->link,CURLOPT_AUTOREFERER, $val);
		curl_setopt($this->link,CURLOPT_MAXREDIRS, 5);
	}
	function userAgent($val)
	{
		curl_setopt($this->link,CURLOPT_USERAGENT, $val);
	}
	function sslVarification($val)
	{
		curl_setopt($this->link,CURLOPT_SSL_VERIFYPEER, $val);
		curl_setopt($this->link, CURLOPT_SSL_VERIFYHOST, $val);
	}
	function addParameter($key,$val)
	{
		$this->parm[$key]=$val;
	}
	function setUrl($url)
	{
		if($url!="")
		{
			$this->url = $url;
			curl_setopt($this->link, CURLOPT_URL, $this->url);
			curl_setopt($this->link, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->link, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		}
	}
	function mathod($val)
	{
		$this->mathod=$val;
	}
	function send()
	{	
		
		if($this->url!="")
		{
			if($this->mathod=="post")
			{
				$fields_string = '';
				//foreach($this->parm as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				//$fields_string = rtrim($fields_string, '&');		
				$encStr="";
				if($this->isolation && $this->isolationKey!="")
				{
					$encStr=$this->isolationKey;
					foreach($this->parm as $k => $v)
					{
						$encStr.=$v;
					}
					$encStr=md5($encStr);
					$this->addParameter("isolationHash",$encStr);
				}
				if(count($this->parm)>0)
				{
					curl_setopt($this->link,CURLOPT_POST, count($this->parm));
					curl_setopt($this->link,CURLOPT_POSTFIELDS, $this->parm);
				}
				
				return curl_exec($this->link);
			}
			else
			{
				$fields_string = '';
				foreach($this->parm as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				$fields_string = rtrim($fields_string, '&');
							
				if(count($this->parm)>0)
				{
					$this->url = $this->url."?".$fields_string ;
					curl_setopt($this->link, CURLOPT_URL, $this->url);
				}
				return curl_exec($this->link);
			}
		}
		else
		{
			return null;
		}
	}

	function close()
	{
		curl_close($this->link);
	}
}
?>