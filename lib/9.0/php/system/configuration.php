<?php
class Configuration
{
	var $data;
	var $filename;
	var $regsitration;
    protected $key = "JUST A KEY";
    protected $cipher = "rijndael-256";
    protected $mode = "cbc";	
	function Configuration()
	{
		$this->init();
	}
	function init()
	{
		global $YAJAN_DATA;

		if(!file_exists($YAJAN_DATA."/config/default.conf"))
		{
			$this->filename=$YAJAN_DATA."/config/config.conf";
			
		}
		else
		{
			$filename = file_get_contents($YAJAN_DATA."/config/default.conf");
			$this->filename=$YAJAN_DATA."/config/$filename.conf";
		}
		
		$this->load();
	}
	function export($filename="export.config.conf")
	{
		global $AUTH,$INFO,$DB_REG,$microVersion,$libVersion;
		$version="1.2";
		$data = array();
		$data['configuration']=$this->data;
		$data['auth']=$AUTH->data;
		$data['info']=$INFO->data;
		$data['registration']=$DB_REG;
		$data['libVersion']=$libVersion;
		$data['microVersion']=$microVersion;
		$data['backupVersion']=$version;
		$str = serialize($data);
		$str = $this->m_encrypt($str);
		
		
		echo "Configuration $version export to $filename\n";
		file_put_contents($filename,$str);
	}
	function import($filename="export.config.conf")
	{
		global $AUTH,$INFO,$DB_REG,$microVersion,$libVersion;
		$cFilename = $this->filename;
		$supportedBackupVersion="1.2";
		$cHome = ($this->data["config"]["YAJAN_DATA"]["value"]);
		$data = file_get_contents($filename);
		$data = $this->m_decrypt($data);
		$data = unserialize($data);
		if(isset($data['backupVersion']))
		{
			/*
			if(!$data['backupVersion']==$supportedBackupVersion)
			{
				echo "Unsupported backup version.\n";
				exit(0);
			}
			*/
			
			$this->data=$data['configuration'];
			$this->data["config"]["YAJAN_DATA"]["value"]=$cHome;
			$this->write();


			$AUTH->data=$data['auth'];
			$AUTH->write();
			
			$INFO->data=$data['info'];
			$INFO->write();
			
			if($data['backupVersion']=="1.2")
			{
				$DB_REG=$data["registration"];
				$DB_REG->save();
			}
			echo "Configuration ".$data['backupVersion']." import from $filename complite.\n";
		}
		else
		{
			echo "invalid backup file.\n";
		}
	}
	function exportPhp($filename,$name)
	{
		file_put_contents($filename,base64_decode($this->data['config'][$name]['value']));
	}
	function getConfigFile()
	{
		global $YAJAN_DATA;
		return $this->filename;
	}
	function getConfigName()
	{
		global $YAJAN_DATA;
		$name = basename($this->filename);
		$name = str_replace(".cond","",$name);
		return $name;
	}
	function changeDefaultConfig($name)
	{
		global $YAJAN_DATA;
		if(file_exists($YAJAN_DATA."/config/$name.conf"))
		{
			file_put_contents($YAJAN_DATA."/config/default.conf",$name);
			$this->init();
			return true;
		}
		else
		{
			return false;
		}
	}
	function restoreDefaultConfig()
	{
		global $YAJAN_DATA;
		unlink($YAJAN_DATA."/config/default.conf");
	}
	function clearConfig()
	{
		global $YAJAN_DATA;
		$this->data=array();
		$this->set("YAJAN_DATA",$YAJAN_DATA);
		$this->set("AUTH_ENABLE","false");
		$this->set("AUTO_POPULATE_RECORDSET","true");
		$this->set("CSS_SYNC_MODE","");
		$this->set("DATABASE_AUTO_OPEN","false");
		$this->set("DB_REDOLOG_FILE","var/log/redo.log");
		$this->set("DEFAULT_DB_OBJECT","db");
		$this->set("DEFAULT_IMPORT_PACKEG","system,ui,db");
		$this->set("EXCEPTION_LOG","false");
		$this->set("EXCEPTION_LOG_FILE","");
		$this->set("FORM_URL_MODE","file");
		$this->set("FREAMWORK_PATH","yajan");
		$this->set("JAVASCRIPT_SYNC_MODE","");
		$this->set("JS_ELEMENT_MODE","dom");
		$this->set("PASSWORD_MASK","*****");
		$this->set("BOOTSTRAP_CSS","true");
		$this->set("YAJAN_SERVER","http://yajan.in");
		
	}
	function load()
	{
		global $PASSWORD_MASK,$SYSTEM,$__yajan_base,$EXEC_MODE;
		if(file_exists($this->filename))
		{
			
			$this->data = file_get_contents($this->filename);
			$this->data = $this->m_decrypt($this->data);
			$this->data = unserialize($this->data);
			
			foreach($this->data['config'] as $k => $v)
			{
				if(isset($v["type"]))
				{
					
					if($v["type"]=="var")
					{
						
						$vx=base64_decode($v["value"]);
						global $$k;
						
						if($k=="YAJAN_DATA")
						{
							if($$k!=$vx)
							{
								if(isset($__yajan_base))
								{
									echo "Yajan start from deferant($__yajan_base) yajan base.\n";
								}
								else
								{
									echo "Yajan configuration error. check YAJAN_DATA parameter\n";
									
									exit(0);									
								}
							}
						}
						if($vx=="true")
						{
							$$k=true;
						}
						else if($vx=="false")
						{
							$$k=false;
						}
						else
						{
							$$k=$vx;
						}
					}
					else
					{
						$data = base64_decode($v["value"]);
						$data = str_replace("<?php","",$data);
						$data = str_replace("<?","",$data);
						$data = str_replace("?>","",$data);
						eval($data);
					}
				}
			}
			if(isset($__yajan_base))
			{
				$YAJAN_DATA=$__yajan_base;
			}
			if(!isset($PASSWORD_MASK))
			{
				$PASSWORD_MASK="********";
			}
			if(isset($PHP_ERROR_REPORTING))
			{
				$SYSTEM->setPhpErrorReporting($PHP_ERROR_REPORTING);
			}
			if(isset($PHP_DISPLAY_ERRORS))
			{
				$SYSTEM->setPhpIni("display_errors", $PHP_DISPLAY_ERRORS);
			}
			if(isset($PHP_DISPLAY_STARTUP_ERRORS))
			{
				$SYSTEM->setPhpIni("display_startup_errors", $PHP_DISPLAY_STARTUP_ERRORS);
			}
			if(isset($PHP_LOG_ERRORS))
			{
				$SYSTEM->setPhpIni("log_errors", $PHP_LOG_ERRORS);
			}
			if(isset($PHP_TRACK_ERRORS))
			{
				$SYSTEM->setPhpIni("track_errors", $PHP_TRACK_ERRORS);
			}
			if(isset($PHP_HTML_ERRORS))
			{
				$SYSTEM->setPhpIni("html_errors", $PHP_HTML_ERRORS);
			}
			if(isset($PHP_ERROR_LOGFILE))
			{
				$SYSTEM->setPhpIni("error_log", $PHP_ERROR_LOGFILE);
			}
			
		}
		else
		{
			echo "configuration not properly loaded.\n";
		}
	}
	function write()
	{
		global $YAJAN_DATA;
		if(!is_dir($YAJAN_DATA."/config"))
		{
			mkdir($YAJAN_DATA."/config");
		}	
		$str = serialize($this->data);
		$str = $this->m_encrypt($str);
		file_put_contents($this->filename,$str);
		$this->init();
	}
	function get($name)
	{
		return base64_encode($this->data['config'][$name]);
	}
	function set($name,$val,$type="var",$safe=false)
	{
		if($safe==true)
		{
			if(!isset($this->data['config'][$name]))
			{
				$this->data['config'][$name]=array("type"=>$type,"value"=>base64_encode($val),"remark"=>"");
			}
		}
		else
		{
			$this->data['config'][$name]=array("type"=>$type,"value"=>base64_encode($val),"remark"=>"");		
		}
	}
	public function m_encrypt($data)
    {
        return (string) 
         (
          mcrypt_encrypt(
           $this->cipher,
           substr(md5($this->key),0,mcrypt_get_key_size($this->cipher, $this->mode)),
           $data,
           $this->mode,
           substr(md5($this->key),0,mcrypt_get_block_size($this->cipher, $this->mode))
          )
         );
    }
	public function m_decrypt($data)
    {
        return (string)
          mcrypt_decrypt(
           $this->cipher,
           substr(md5($this->key),0,mcrypt_get_key_size($this->cipher, $this->mode)),
           ($data),
           $this->mode,
           substr(md5($this->key),0,mcrypt_get_block_size($this->cipher, $this->mode))
          );
    }
	private function enc2_encode($str)
	{
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($key);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $str, MCRYPT_MODE_CBC, $iv);
		return $iv . $ciphertext;
		
	}
	private function enc2_decode($str)
	{
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	}	
	public function removeKey($name)
	{
		unset($this->data['config'][$name]);
	}
}
$CONFIGURATION = new Configuration();
?>
