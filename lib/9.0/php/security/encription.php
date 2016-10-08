<?php
class Encryption
{
	var $format;
	var $salt;
	var $cFormat;
	var $defaultEnc;
	var $zip;
	var $enc64;
	var $saltIntigration;
	var $zipLevel;
	function Encryption($salt="",$enc="ENC1")
	{
		$this->defaultEnc = $enc;
		$this->format =$this->defaultEnc;
		$this->salt = $salt;
		if($this->salt=="")
		{
			$this->salt = session_id();
		}
		$this->cFormat = "ASCI";;
		$this->zip = true;
		$this->enc64 = true;
		$this->saltIntigration = false;
		$this->zipLevel = 9;
	}
	function salt($salt="")
	{
		if($salt!="")
		{
			$this->salt= $salt;
		}
		return $this->salt;
	}
	function crypt($str,$cFormat="ASCI")
	{
		$format = $this->format;
		$this->cFormat = $cFormat;
		
		if($cFormat == "ASCI")
		{
			if($this->format == "ASCI")
			{
				return $str;
			}
			else if($this->format == "BASE64")
			{
				return base64_encode($str);
			}
			else if($this->format == "MD5")
			{
				return md5($str);
			}
			else if($this->format == "ENC1")
			{
				return $this->enc1_encode($str);
			}
			else if($this->format == "ENC2")
			{
				return $this->enc2_encode($str);
			}
		}
		else if($cFormat == "BASE64")
		{
			if($this->format == "ASCI")
			{
				return base64_decode($str);
			}
			else if($this->format == "BASE64")
			{
				return $str;
			}
			else if($this->format == "MD5")
			{
				return md5(base64_decode($str));
			}
			else if($this->format == "ENC1")
			{
				return $this->enc1_encode(base_decode($str));
			}
			else if($this->format == "ENC2")
			{
				return $this->enc2_encode(base_decode($str));
			}
		}
		else if($cFormat == "ENC1")
		{
			if($this->format == "ASCI")
			{
				return $this->enc1_decode($str);
			}
			else if($this->format == "BASE64")
			{
				return base_encode($this->enc1_decode($str));
			}
			else if($this->format == "MD5")
			{
				return md5($this->enc1_decode($str));
			}
			else if($this->format == "ENC1")
			{
				return $str;
			}
			else if($this->format == "ENC2")
			{
				return $this->enc2_encode($this->enc1_decode($str));
			}
		}
		else if($cFormat == "ENC2")
		{
			if($this->format == "ASCI")
			{
				return $this->enc2_decode($str);
			}
			else if($this->format == "BASE64")
			{
				return base_encode($this->enc2_decode($str));
			}
			else if($this->format == "MD5")
			{
				return md5($this->enc2_decode($str));
			}
			else if($this->format == "ENC1")
			{
				return $this->enc1_encode($this->enc2_decode($str));
			}
			else if($this->format == "ENC2")
			{
				return $str;
			}
		}
	}
	function encrypt($str,$format="ENC1")
	{
		$this->format = $format;
		$this->cFormat = "ASCI";
		return $this->crypt($str,$this->cFormat);
	}
	function decrypt($str,$cFormat="ENC1")
	{
		$this->format = "ASCI";
		$this->cFormat = $cFormat;		
		return $this->crypt($str,$this->cFormat);
	}
	function setFormat($format)
	{
		$this->format = $format;
	}
	private function enc2_encode($str,$salt = "")
	{
		if($salt!="")
		{
			$this->salt = $salt;
		}
		if($this->salt!="")
		{
			$salt = $this->salt;
		}
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($salt);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $str, MCRYPT_MODE_CBC, $iv);
		return $iv . $ciphertext;
		
	}
	private function enc2_decode($str,$salt = "")
	{
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	}
	private function enc1_encode($str,$salt = "")
	{
		if($salt!="")
		{
			$this->salt = $salt;
		}
		if($this->salt!="")
		{
			$salt = $this->salt;
		}
		$p=0;
		$out="";
		for($i=0;$i<strlen($str);$i++)
		{
			if($p==strlen($salt))
			{
				$p=0;
			}
			$ix = (ord($str[$i])+ord($salt[$p]));
			$ch = $ix%255;
			$ix = (int)($ix/255)+1;
			$out.=chr($ix).chr($ch);
			$p++;
		}
		if($this->saltIntigration)
		{
			$ch = chr(strlen($this->salt));
			$ch.=$this->salt;
			$out=$ch.$out;
		}
		
		if($this->zip)
		{
			$c = new Compression();
			$out = $c->compress($out);
		}
		if($this->enc64)
		{
			$out = base64_encode($out);
		}
		return $out;
	}
	private function enc1_decode($str,$salt = "")
	{
		if($this->enc64)
		{
			$str = base64_decode($str);
		}
		if($this->zip)
		{
			$c = new Compression();
			$str = $c->uncompress($str);
		}
		
		$i=0;
		if($salt!="")
		{
			$this->salt = $salt;
		}
		
		if($this->saltIntigration)
		{
			$l = ord($str[0]);
			$salt = substr($str,1,$l);
			$i=$l+1;
			//echo $salt;
		}
		
		if($this->salt!="")
		{
			$salt = $this->salt;
		}
		
		$p=0;
		$out="";
		
		for($i;$i<strlen($str);$i=$i+2)
		{
			if($p==strlen($salt))
			{
				$p=0;
			}
			$ix = ord($str[$i])-1;
			$ch = ord($str[$i+1]);
			$ch = (($ch+($ix*255))-ord($salt[$p]));
			$out.=chr($ch);
			$p++;
		}
		return $out;
	}
}
?>