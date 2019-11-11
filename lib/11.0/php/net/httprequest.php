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
	var $customeHeader;
	var $resp;
	var $responceHeader;
	var $formData;
	var $filesize;
	var $cookies;
	var $cookieResp;
	var $info;
	var $agent;
	var $errorCode;
	var $sslVersion;
	function __construct($url="")
	{
		$this->errorCode=0;
		$this->responceHeader=false;
		$this->customeHeader=array();
		$this->formData=false;
		$this->timeout=5;
		$this->isolation=false;
		$this->isolationKey="";
		$this->link =  curl_init();
		$this->parm = array();
		$this->cookieResp = array();
		$this->mathod="post";
		$this->files = array();
		$this->cookies=array();
		$this->cookieResp=null;
		$this->info=null;
		$this->filesize=0;
		$this->sslVersion=2;
		define("COOKIE_FILE", "cookie.txt");
		curl_setopt ($this->link, CURLOPT_COOKIEJAR, COOKIE_FILE); 
		curl_setopt ($this->link, CURLOPT_COOKIEFILE, COOKIE_FILE); 
		$this->agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
		if($url!="")
		{
			$this->url = $url;
			curl_setopt($this->link, CURLOPT_URL, $this->url);
			curl_setopt($this->link, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->link, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		//	curl_setopt($this->link, CURLOPT_SSLVERSION,3); 
		}
		$this->userAgent($this->agent);
	}
	function setSslVersion($version=3)
	{
		$this->sslVersion=$version;
		curl_setopt($this->link, CURLOPT_SSLVERSION,3); 
	}
	function addCookie($key,$value)
	{
		$a = array();
		$a["key"]=$key;
		$a["value"]=$value;
		$this->cookies[count($this->cookies)]=$a;
	}
	function secretIsolation($val)
	{
		$this->isolation=$val;
	}
	function isolationKey($key)
	{
		$this->isolationKey=$key;
	}
	
	function addFile($name,$filepath)
	{
		if(file_exists($filepath))
		{
			if($this->formData==false)
			{
				$this->addHeader("Content-Type: multipart/form-data");
			}
			$this->filesize = $this->filesize+(filesize($filepath));
			$this->formData=true;
			
			
			
			if (function_exists('curl_file_create')) 
			{ // php 5.6+
				$cFile = curl_file_create($filepath);
			} 
			else 
			{ // 
				$cFile = '@' . realpath($filepath);
			}
			$this->parm[$name]=$cFile;
			return true;
		}
		else
		{
			return false;
		}
	}
	function responceHeader($val)
	{
		$this->responceHeader=$val;
	}
	function getHeaderSize()
	{
		return curl_getinfo($this->link, CURLINFO_HEADER_SIZE);
	}
	function addHeader($header)
	{
		$this->customeHeader[count($this->customeHeader)]=$header;
	}
	function setSourceIp($ip)
	{
		$this->addHeader("REMOTE_ADDR: $ip");
		$this->addHeader("X_FORWARDED_FOR: $ip");
		
		
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
	function getHeader()
	{
		
		return substr($this->resp, 0, $this->info['header_size']);
	}
	function getResponceBody()
	{
		if($this->responceHeader)
		{

			//echo $info["header_size"];
			//echo substr($this->resp, $info['header_size'],strlen($this->resp));
			
			$this->info = curl_getinfo($this->link);
				
			if($this->info['download_content_length']>0)
			{
				return substr($this->resp, strlen($this->resp)-$this->info['download_content_length'],strlen($this->resp));	
			}
			else
			{
				return substr($this->resp, $this->info['header_size'],strlen($this->resp));	
			}
			
		}
		else
		{
			
			return $this->resp;
		}
	}
	function cookie($v)
	{
		$exclude = array("_ga","PHPSESSID","_gid");
		if($v)
		{
			$this->responceHeader=true;
			foreach ($_COOKIE as $key => $value) {
				if(array_search($key,$exclude)===false)
				$this->addCookie($key,$value);
				# code...
			}
		}

	}
	function getError()
	{
		$error_codes=array(
		"0" => "",
		"1" => 'CURLE_UNSUPPORTED_PROTOCOL', 
		"2" => 'CURLE_FAILED_INIT', 
		"3" => 'CURLE_URL_MALFORMAT', 
		"4" => 'CURLE_URL_MALFORMAT_USER', 
		"5" => 'CURLE_COULDNT_RESOLVE_PROXY', 
		"6" => 'CURLE_COULDNT_RESOLVE_HOST', 
		"7" => 'CURLE_COULDNT_CONNECT', 
		"8" => 'CURLE_FTP_WEIRD_SERVER_REPLY',
		"9" => 'CURLE_REMOTE_ACCESS_DENIED',
		"11" => 'CURLE_FTP_WEIRD_PASS_REPLY',
		"13" => 'CURLE_FTP_WEIRD_PASV_REPLY',
		"14"=>'CURLE_FTP_WEIRD_227_FORMAT',
		"15" => 'CURLE_FTP_CANT_GET_HOST',
		"17" => 'CURLE_FTP_COULDNT_SET_TYPE',
		"18" => 'CURLE_PARTIAL_FILE',
		"19" => 'CURLE_FTP_COULDNT_RETR_FILE',
		"21" => 'CURLE_QUOTE_ERROR',
		"22" => 'CURLE_HTTP_RETURNED_ERROR',
		"23" => 'CURLE_WRITE_ERROR',
		"25" => 'CURLE_UPLOAD_FAILED',
		"26" => 'CURLE_READ_ERROR',
		"27" => 'CURLE_OUT_OF_MEMORY',
		"28" => 'CURLE_OPERATION_TIMEDOUT',
		"30" => 'CURLE_FTP_PORT_FAILED',
		"31" => 'CURLE_FTP_COULDNT_USE_REST',
		"33" => 'CURLE_RANGE_ERROR',
		"34" => 'CURLE_HTTP_POST_ERROR',
		"35" => 'CURLE_SSL_CONNECT_ERROR',
		"36" => 'CURLE_BAD_DOWNLOAD_RESUME',
		"37" => 'CURLE_FILE_COULDNT_READ_FILE',
		"38" => 'CURLE_LDAP_CANNOT_BIND',
		"39" => 'CURLE_LDAP_SEARCH_FAILED',
		"41" => 'CURLE_FUNCTION_NOT_FOUND',
		"42" => 'CURLE_ABORTED_BY_CALLBACK',
		"43" => 'CURLE_BAD_FUNCTION_ARGUMENT',
		"45" => 'CURLE_INTERFACE_FAILED',
		"47" => 'CURLE_TOO_MANY_REDIRECTS',
		"48" => 'CURLE_UNKNOWN_TELNET_OPTION',
		"49" => 'CURLE_TELNET_OPTION_SYNTAX',
		"51" => 'CURLE_PEER_FAILED_VERIFICATION',
		"52" => 'CURLE_GOT_NOTHING',
		"53" => 'CURLE_SSL_ENGINE_NOTFOUND',
		"54" => 'CURLE_SSL_ENGINE_SETFAILED',
		"55" => 'CURLE_SEND_ERROR',
		"56" => 'CURLE_RECV_ERROR',
		"58" => 'CURLE_SSL_CERTPROBLEM',
		"59" => 'CURLE_SSL_CIPHER',
		"60" => 'CURLE_SSL_CACERT',
		"61" => 'CURLE_BAD_CONTENT_ENCODING',
		"62" => 'CURLE_LDAP_INVALID_URL',
		"63" => 'CURLE_FILESIZE_EXCEEDED',
		"64" => 'CURLE_USE_SSL_FAILED',
		"65" => 'CURLE_SEND_FAIL_REWIND',
		"66" => 'CURLE_SSL_ENGINE_INITFAILED',
		"67" => 'CURLE_LOGIN_DENIED',
		"68" => 'CURLE_TFTP_NOTFOUND',
		"69" => 'CURLE_TFTP_PERM',
		"70" => 'CURLE_REMOTE_DISK_FULL',
		"71" => 'CURLE_TFTP_ILLEGAL',
		"72" => 'CURLE_TFTP_UNKNOWNID',
		"73" => 'CURLE_REMOTE_FILE_EXISTS',
		"74" => 'CURLE_TFTP_NOSUCHUSER',
		"75" => 'CURLE_CONV_FAILED',
		"76" => 'CURLE_CONV_REQD',
		"77" => 'CURLE_SSL_CACERT_BADFILE',
		"78" => 'CURLE_REMOTE_FILE_NOT_FOUND',
		"79" => 'CURLE_SSH',
		"80" => 'CURLE_SSL_SHUTDOWN_FAILED',
		"81" => 'CURLE_AGAIN',
		"82" => 'CURLE_SSL_CRL_BADFILE',
		"83" => 'CURLE_SSL_ISSUER_ERROR',
		"84" => 'CURLE_FTP_PRET_FAILED',
		"84" => 'CURLE_FTP_PRET_FAILED',
		"85" => 'CURLE_RTSP_CSEQ_ERROR',
		"86" => 'CURLE_RTSP_SESSION_ERROR',
		"87" => 'CURLE_FTP_BAD_FILE_LIST',
		"88" => 'CURLE_CHUNK_FAILED');
		return $error_codes[$this->errorCode];

	}
	function send()
	{	

		if($this->url!="")
		{
			
			//curl_setopt($this->link, CURLOPT_SAFE_UPLOAD, true);
			curl_setopt($this->link, CURLOPT_BUFFERSIZE, 128);
			
			if($this->responceHeader)
			{
				curl_setopt($this->link, CURLOPT_VERBOSE, 1);
				curl_setopt($this->link,CURLOPT_HEADER, 1);

			}
			
			if(count($this->cookies)>0)
			{
				$cStr = "";

				for($i=0;$i<count($this->cookies);$i++)
				{
					$cStr.=";".$this->cookies[$i]["key"]."=".$this->cookies[$i]["value"];
				}

				$cStr=ltrim($cStr,";");

				$this->addHeader("Cookie: ".$cStr);
				curl_setopt($this->link,CURLOPT_HEADER, 1);
				$this->responceHeader=true;
			}
			
			curl_setopt($this->link,CURLOPT_HTTPHEADER, $this->customeHeader);
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
					curl_setopt($this->link,CURLOPT_POST, true);
					curl_setopt($this->link,CURLOPT_POSTFIELDS, $this->parm);
				}
				if($this->filesize>0)
				{
					curl_setopt($this->link,CURLOPT_INFILESIZE,$this->filesize);
				}
				$a = curl_exec($this->link);
				if($a===false)
				{
					$this->errorCodr=curl_errno($this->link);
				}
				$this->resp = $a;

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
				$a = curl_exec($this->link);
				if($a===false)
				{
					$this->errorCodr=curl_errno($this->link);
				}
				$this->resp = $a;
			}
//echo "++++".$this->resp."++++<br>";
			if($this->resp===false)
			{
				return $this->resp;
			}
			$this->info = curl_getinfo($this->link);
			$header_content = substr($this->resp, 0, $this->info['header_size']);
				
			$a = explode("\n",$header_content);

			for ($i=0; $i < count($a); $i++) { 
				$t = explode(":",$a[$i]);
				if($t[0]=="Set-Cookie")
				{
					$t = explode("=",trim($t[1]));
					$this->cookieResp[count($this->cookieResp)]=$t;
				}
			}
			$str = $this->getResponceBody();
			return $str;
		}
		else
		{
			return null;
		}
	}
	function getCookieResponce()
	{
		return $this->cookieResp;
	}
	function close()
	{
		if($this->link)
		{
			curl_close($this->link);
		}
		
	}
}
?>
