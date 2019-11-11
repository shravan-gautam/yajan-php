<?php
class IMAPMailBox
{
	var $connectionString;
	var $username;
	var $password;
	var $connection;
	function __construct($connectionString,$username,$password)
	{
		$this->connectionString = $connectionString;
		$this->username = $username;
		$this->password = $password;
	}
	function open()
	{
		$this->connection = imap_open($this->connectionString, $this->username, $this->password);
	}
	function close()
	{
		imap_close($this->connection);
	}
	function search($query)
	{
		$m = new IMAPQuery($this);
		$m->search($query);
		return $m; 
	}
	function fetchAll()
	{
		$m = new IMAPQuery($this);
		$m->getMessageCount();
		return $m; 
	}
}
class IMAPEmail
{
	var $mid;
	var $info;
	var $header;
	var $mailbox;
	function __construct(IMAPMailBox $mailbox, $mid)
	{
		$this->mid = $mid;
		$this->mailbox = $mailbox;
		$this->info = imap_fetch_overview($this->mailbox->connection,$this->mid);
		if($this->info[0]->uid!="")
		{
			$this->header = imap_headerinfo($this->mailbox->connection,$this->mid);
		}
	}
	function get($name)
	{
		return $this->info[0]->$name;
	}
	function getText()
	{
		return imap_fetchbody($this->mailbox->connection,$this->mid,"1"); 
	}
	function getRawHeader()
	{
		return imap_fetchbody($this->mailbox->connection,$this->mid,"0"); 
	}
	function getAttachmentHeader()
	{
		return imap_fetchbody($this->mailbox->connection,$this->mid,"2"); 
	}
	function getAttachment()
	{
		return imap_fetchbody($this->mailbox->connection,$this->mid,"3"); 
	}
	function getHeader($name)
	{
		return $this->header->$name;
	}
	function setMark($flage)
	{
		imap_setflag_full($this->mailbox->connection, $this->mid, $flage);
	}
	function markRead()
	{
		$this->setMark("\\Seen");
	}
	function markFlage()
	{
		$this->setMark("\\Flagged");
	}
}
class IMAPQuery
{
	var $mailbox;
	var $data;
	var $index;
	function __construct(IMAPMailBox $mailbox)
	{
		$this->mailbox=$mailbox;
		$data=array();
	}
	function getMessageCount()
	{
		$n = imap_num_msg($this->mailbox->connection);
		for($i=0;$i<$n;$i++)
		{
			$this->data[$i]=$i;
		}
		return count($this->data);
	}
	function search($query)
	{
		$this->data = imap_search($this->mailbox->connection, $query);
		return count($this->data);
	}
	function count()
	{
		return count($this->data);
	}
	function moveRecord($index)
	{
		$this->index = $index;
	}
	function getEmail()
	{	
		$mid = $this->data[$this->index];
		return new IMAPEmail($this->mailbox,$mid);
	}
	function last()
	{
		$this->index=$this->count();
	}
}

?>