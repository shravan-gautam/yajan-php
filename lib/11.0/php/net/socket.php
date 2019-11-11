<?php
class Socket
{
	var $host;
	var $port;
	var $timeout;
	var $socket;
	var $conn;
	function __construct($socket=null)
	{
		if($socket==null)
		{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not bind to socket\n");
		}
		else
		{
			$this->socket = $socket;
		}

	}
	function open($host,$port)
	{
		$this->host=$host;
		$this->port=$port;
		if($this->host!="" && $this->port!="")
		{
			$this->result = socket_bind($this->socket, $host, $port) or die("Could not bind to socket\n");
		}
	}
	function listen()
	{
		$this->result = socket_listen($this->socket, 3) or die("Could not set up socket listener\n");
		$spawn = socket_accept($this->socket) or die("Could not accept incoming connection\n");
		return new Socket($spawn);
	}
	function read()
	{
		return socket_read($this->socket, 1024) or die("Could not read input\n");
	}
	function write($str)
	{
		socket_write($this->socket,$str, strlen ($str)) or die("Could not write output\n");
	}
	function close()
	{
		socket_close($this->socket);
	}
}
?>