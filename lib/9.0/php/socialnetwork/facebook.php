<?php
require "$LIB_PATH/php/socialnetwork/facebook/facebookapi.php";
require "$LIB_PATH/php/socialnetwork/facebook/facebookpage.php";
require "$LIB_PATH/php/socialnetwork/facebook/facebookevent.php";
class Facebook
{
	private $appId;
	private $secKey;
	var $link;
	private $user;
	private $userProfile;
	var $statusUrl;
	var $loginUrl;
	var $logoutUrl;
	var $error;
	var $errStat;
	function Facebook($id,$key)
	{
		$this->link = new FacebookApi(array('appId'  => $id,'secret' => $key,'fileUpload'=>true,'cookie'=>true ));	
		$this->user = $this->link->getUser();
		if($this->user)
		{
			$this->error = array();
			try 
			{
				$this->userProfile = $this->link->api('/me');
				$this->logoutUrl = $this->link->getLogoutUrl();
				$this->statusUrl = "";
				$this->loginUrl = "";
			} 
			catch (FacebookApiException $e) 
			{
				$this->error = $e;
				$this->user = null;
			}
		}
		else
		{
			$this->statusUrl = $this->link->getLoginStatusUrl();
			$this->loginUrl = $this->link->getLoginUrl();
			
			$this->logoutUrl = "";
		}
	}
	function run($cmd)
	{
		try
		{
			return $this->link->api($cmd);
		}
		catch(Exception $ex)
		{
			$this->error = $ex;
		}
	}
	function isLogin()
	{
		if($this->user)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getProfile($user)
	{
		$this->error = array();
		try
		{
			return $this->link->api('/'.$user);
		}
		catch(Exception $ex)
		{
			$this->errpr = $ex;
			return false;
		}
	}
	function loginButton()
	{
		if(!$this->user)
		{
			echo '<a href="'.$this->loginUrl.'">Login with Facebook</a>';
		}
	}
	function logoutButton()
	{
		if($user)
		{
			echo '<a href="'.$this->logoutUrl.'">Logout</a>';
		}
	}
	function photo()
	{
		echo '<img src="https://graph.facebook.com/'.$this->user.'/picture">';
	}
	function sendMessage($message,$image='',$url='',$profile='')
	{
		if($profile=="")
		{
			$profile="me";
		}
		$this->error = array();
		if($this->user)
		{
			try
			{
				$access_token = $this->link->getAccessToken();
				$args = array();
				$args['message']=$message;
				$args['access_token'] = $access_token;
				if($url!='')
				{
					$args['link']=$url;
				}
				if($image!='')
				{
					//$args['source']='@'.$image;
				}
				return $this->link->api('/'.$profile.'/feed', 'POST',$args);
			}
			catch(Exception $ex)
			{
				$this->error =$ex;
				return false;
			}
		}
	}
	function uploadPhoto($filename,$caption,$profile='')
	{
		if($profile=="")
		{
			$profile="me";
		}
		$filename = "@".realpath($filename);
		$this->link->setFileUploadSupport(true);  
		$access_token = $this->link->getAccessToken();

		$args = array(  
			'message' => $caption,  
			"access_token" => $access_token,  
			"image" => $filename  
		);
		try
		{
			return $this->link->api('/'.$profile.'/photos', 'post', $args);
		}
		catch(Exception $ex)
		{
			$this->error = $ex;
			return false;
		}
	}
	function execute($q)
	{
		$q=$q.";";
		try
		{
			$access_token = $this->link->getAccessToken();
			$argv = array(  
					'method' => 'fql.query',  
					'access_token'=>$access_token,
					'query' => $q  
			);
			return $this->link->api($argv);  
		}
		catch(Exception $ex)
		{
			
			$this->error = $ex;
			return false;
		}
	}
	function getErrorMessage()
	{
		if(gettype($this->error)=="object")
		{
			$er = $this->error->getResult();
			echo $er['error_msg'];
		}
	}
	function getErrorCode()
	{
		if(gettype($this->error)=="object")
		{
			$er = $this->error->getResult();
			echo $er['error_code'];
		}
	}
	function getErrorBacktress()
	{
		if(gettype($this->error)=="object")
		{
			$er = $this->error->getResult();
			print_r($er);
		}
	}
	function getPage($page)
	{
		return new FacebookPage($this,$page);
	}
	function getLike($page)
	{
		return $this->execute("select name,new_like_count,fan_count from page where page_id = $page");
	}
	function getEvent($id)
	{
		$evt = new FacebookEvent($this,$id);
		return $evt;
	}
}
?>