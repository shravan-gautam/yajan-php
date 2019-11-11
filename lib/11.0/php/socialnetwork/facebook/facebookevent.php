<?php
class FacebookEvent
{
	private $facebook;
	private $id;
	var $data;
	function FacebookEvent($obj,$id)
	{
		$this->facebook = $obj;
		$this->id = $id;
		$this->data = array();
		$this->data = $this->facebook->link->api("/$id?date_format=U");
		$q="select all_members_count,attending_count,unsure_count,declined_count,not_replied_count from event where eid = $id";
		$this->data['member_count'] = $this->facebook->execute($q);
		$this->data['member_count'] = $this->data['member_count'][0];
	}
	function isAvailable()
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
	function getJoined()
	{	
		return $this->data['member_count']['all_members_count'];
	}
	function getDescription()
	{
		return $this->data['description'];
	}
	function getName()
	{
		return $this->data['name'];
	}
	function getStartDate($format='l, d-M-y H:i:s T')
	{
		$dt = $this->data['start_time'];
		return $dt;
	}
	function getDuration()
	{
		return $this->data['is_date_only'];
	}
	function getLocation()
	{
		return $this->data['location'];
	}
	function getVenue()
	{
		return $this->data['venue'];
	}
	function getOwner()
	{
		return $this->data['owner'];
	}
	function getDetail()
	{
		$page = $this->id;
		try
		{
			$this->facebook->link->api("/$page/insights/page_fans/lifetime?since=1");
		}
		catch(Exception $ex)
		{
			print_r($ex);
		}
	}
		
}
?>