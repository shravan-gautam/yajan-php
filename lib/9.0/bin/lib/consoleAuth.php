<?php

class ConsoleAuth
{
	var $authFile;
	var $enc;
	var $data;
	var $userName;
	function ConsoleAuth()
	{
		global $YAJAN_DATA;
		$this->userName="DEFAULT";
		$this->init();
	}

	function login($a)
	{
		global $CS;
		$this->load();

		if($a!="")
		{
			$user = "";
			$pass = "";
			$a=explode("/",$a);
			/*
			for($i=0;$i<count($a);$i++)
			{
				$ax = explode("=",$a[$i]);
				if($ax[0]=="user")
				{
					$user=$ax[1];
				}
				else if($ax[0]=="password")
				{
					$pass=$ax[1];
				}
			}
			*/
			$user=$a[0];
			$pass=$a[1];
			if($this->checkUser($user,$pass))
			{
				$this->userName=strtoupper($user);
				return true;
			}
		}
		else
		{
			for($i=0;$i<3;$i++)
			{
				$CS->setFGColor("light_cyan");
				$CS->cout("Enter Username ");
				$user = $CS->read();
				$password = $CS->password();
				$user = strtoupper($user);
				if($this->checkUser($user,$password))
				{
					$this->userName=$user;
					return true;
				}
				$CS->setFGColor("red");
				$CS->cout("Invalid username or password\n");
			}
		}
		return false;	
	}
	function checkUser($user,$password)
	{
		$user = strtoupper($user);
		$password = md5($password);
		for($i=0;$i<count($this->data['user']);$i++)
		{
			if($this->data['user'][$i][0]==$user && $this->data['user'][$i][1] == $password)
			{
				$this->userName=strtoupper($user);
				return true;
			}
		}
		return false;
	}
	function init()
	{
		global $YAJAN_DATA;
		if($YAJAN_DATA=="")
		{
			echo "Invalid yajan inventory path.";
			exit(0);
		}
		$this->enc = new ENC();
		$this->authFile = "$YAJAN_DATA/config/auth.conf";
		
		$this->data = array();
		$this->data['user']=array();
		$this->data['group']=array();
		$this->data['user_group']=array();
		if(!file_exists($this->authFile))
		{
			$this->write();
		}
		$this->load();
		
	}
	function userExistInGroup($user)
	{
		$user = strtoupper($user);
		for($i=0;$i<count($this->data['user_group']);$i++)
		{
			if($this->data['user_group'][$i][0]==$user)
			{
				return true;
			}
		}
		return false;
	}
	function removeUserFromAllGroup($user)
	{
		$user = strtoupper($user);
		$temp = array();
		for($i=0;$i<count($this->data['user_group']);$i++)
		{
			if($this->data['user_group'][$i][0]!=$user)
			{
				$temp[count($temp)]=$this->data['user_group'][$i];
			}
		}
		$this->data['user_group']=$temp;
		$this->write();
		return true;
	}
	function removeUser($user)
	{
		$user = strtoupper($user);
		if($this->isUser($user) )
		{
			if($this->removeUserFromAllGroup($user))
			{
				$temp = array();
				for($i=0;$i<count($this->data['user']);$i++)
				{
					if($this->data['user'][$i][0]!=$user)
					{
						$temp[count($temp)]=$this->data['user'][$i];
					}
				}
				$this->data['user']=$temp;
				$this->write();
				return true;
			}
			
		}
		return false;
	}
	function removeUserGroup($user,$group)
	{
		$user = strtoupper($user);
		$group = strtoupper($group);
		if($this->isUserGroup($user,$group))
		{
			$temp = array();
			for($i=0;$i<count($this->data['user_group']);$i++)
			{
				if($this->data['user_group'][$i][0]!=$user || $this->data['user_group'][$i][1]!=$group)
				{
					$temp[count($temp)]=$this->data['user_group'][$i];
				}
			}
			$this->data['user_group']=$temp;
			$this->write();
			return true;
		}
		return false;
	}
	function load()
	{
		$this->data = file_get_contents($this->authFile);
		$this->data = $this->enc->m_decrypt($this->data);
		$this->data = unserialize($this->data);
	}
	function isUser($name)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->data['user']);$i++)
		{
			if(trim($this->data['user'][$i][0])==$name)
			{
				return true;
			}
		}
		return false;
	}
	function changeUserPassword($user,$pass)
	{
		
		$name = strtoupper($user);
		//echo $name;
		for($i=0;$i<count($this->data['user']);$i++)
		{
			
			if(trim($this->data['user'][$i][0])==$name)
			{
				$this->data['user'][$i][1]=md5($pass);
				$this->write();
				return true;
			}
		}
		return false;
	}
	function addUser($name,$pass)
	{
		$name = strtoupper($name);
		if($this->isUser($name)==false)
		{
			$n  = count($this->data['user']);
			$this->data['user'][$n]=array($name,md5($pass));
			$this->write();
			return true;
		}
		return false;
	}
	function isGroup($name)
	{
		$name = strtoupper(trim($name));

		for($i=0;$i<count($this->data['group']);$i++)
		{
			if(trim($this->data['group'][$i])==$name)
			{
				return true;
			}
		}	
		return false;
	}
	function addGroup($name)
	{
		$name = strtoupper($name);
		
		if($this->isGroup($name)==false)
		{
			$n  = count($this->data['group']);
			$this->data['group'][$n]=$name;
			$this->write();
			return true;
		}
		return false;
	}
	function getUserGroup($userName="")
	{
		$r = new Recordset();
		$r->addColumns("name","code");
		if($userName=="")
		{
			$name = $this->userName;
		}
		else
		{
			$name = $userName;
		}
		for($i=0;$i<count($this->data['user_group']);$i++)
		{
			$obj = array();
			foreach($this->data['user_group'][$i] as $k => $v)
			{
				$obj[count($obj)]=$v;
			}
			if(trim($obj[0])==trim($name) )
			{
				$r->add($obj[1],$obj[1]);
			}
		}
		return $r;
	}
	function isMemberOf($group)
	{
		if($this->userName=="DEFAULT")
		{
			return true;
		}
		
		return $this->isUserGroup($this->userName,$group);
	}	
	function isMemberOff($group)
	{
		
		if($this->userName=="DEFAULT")
		{
			return true;
		}
		return $this->isUserGroup($this->userName,$group);
	}
	function isUserGroup($name,$group)
	{
		$name = strtoupper($name);
		$group = strtoupper($group);
		for($i=0;$i<count($this->data['user_group']);$i++)
		{
			$obj = array();
			foreach($this->data['user_group'][$i] as $k => $v)
			{
				$obj[count($obj)]=$v;
			}
			//echo trim($obj[0])."==".trim($name)." && ".trim($obj[1])."==".trim($group);
			if(trim($obj[0])==trim($name) && trim($obj[1])==trim($group))
			{
				return true;
			}
		}
		
		return false;
	}
	function addUserGroup($name,$group)
	{
		global $CS;
		$name = strtoupper($name);
		$group = strtoupper($group);
		
		if($this->isUser($name) && $this->isGroup($group))
		{
			
			if($this->isUserGroup($name,$group)==false)
			{
				
				$n  = count($this->data['user_group']);
				$this->data['user_group'][$n]=array($name,$group);
				$this->write();
				return true;
			}
			else
			{
				$CS->showError("user allrady exist in group");
			}
		}
		else
		{
			$CS->showError("user or group invalid.");
		}
		return false;
	}
	function write()
	{
		global $YAJAN_DATA;
		
		if(!is_dir($YAJAN_DATA."/config"))
		{
			mkdir($YAJAN_DATA."/config");
		}	
		$str = serialize($this->data);
		$str = $this->enc->m_encrypt($str);
		file_put_contents($this->authFile,$str);
		$this->init();
	}
}
?>