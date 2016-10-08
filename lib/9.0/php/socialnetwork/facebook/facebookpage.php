<?php
class FacebookPage
{
	private $data;
	private $facebook;
	private $id;
	private $userList;
	function FacebookPage($fbObject,$id)
	{
		$this->data="";
		$this->facebook = $fbObject;
		$this->id=$id;
		$this->load();
	}
	function load()
	{
		$page = $this->id;
		if($this->facebook->isLogin())
		{
			$this->data = $this->facebook->link->api("/$page");
			//print_r($this->data);
			
			//$q="select fan_count from page where page_id = $page";
			//$resp = $this->facebook->execute($q);
			//print_r($resp);
			//if(count($resp)>0)
			//{
				//$this->data['fql'] = $resp[0];
			//}
			
			
			//$lk = $this->facebook->link->api("/mashable");
			//print_r($lk);
		}
	}
	function isAvilable()
	{
		if(count($this->data)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getName()
	{
		if($this->isAvilable())
		{
			return $this->data['from']['name'];
		}
	}
	function getLike()
	{
		if($this->isAvilable())
		{
			return $this->data['likes'];
		}
	}
	function getLikeUsers()
	{
		$page = $this->id;
		if($this->fbObject->isLogin())
		{
			$q="select uid,profile_section from page_fan where page_id = $page";
			$resp = $this->fbObject->execute($q);
			if(count($resp)>0)
			{
				print_r($resp);
				$this->data = $resp;
			}
		}
	}
}
?>