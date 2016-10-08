<?php
class IPAddress
{
	var $ip;
	function IPAddress($ip="")
	{
		if($ip=="" && isset($_SERVER["REMOTE_ADDR"]))
		{
			$this->ip=$_SERVER["REMOTE_ADDR"];
		}
		else
		{
			$this->ip=$ip;
		}
	
	}
	public function getIp()
	{
		return $this->ip;
	}
	public function getClientIp()
	{
		return $_SERVER["REMOTE_ADDR"];
	}
    public function cidr2netmask($cidr)
    {
        for( $i = 1; $i <= 32; $i++ )
        $bin .= $cidr >= $i ? '1' : '0';

        $netmask = long2ip(bindec($bin));

        if ( $netmask == "0.0.0.0")
        return false;

		return $netmask;
    }
    public function cidr2network($ip, $cidr)
    {
        $network = long2ip((ip2long($ip)) & ((-1 << (32 - (int)$cidr))));

		return $network;
    }
    public function netmask2cidr($netmask)
    {
        $bits = 0;
        $netmask = explode(".", $netmask);

        foreach($netmask as $octect)
        $bits += strlen(str_replace("0", "", decbin($octect)));

		return $bits;
    }
    public function match($ip, $network, $cidr)
    {
        if ((ip2long($ip) & ~((1 << (32 - $cidr)) - 1) ) == ip2long($network))
        {
            return true;
        }
		return false;
    }
	public function isInNetwork($network,$ip="")
	{
		
		if($ip=="")
		{
			$ip=$this->ip;
		}
		$cidr=explode("/",$network);
		$network = $cidr[0];
		$cidr=$cidr[1];
		return $this->match($ip,$network,$cidr);
	}
	function __toString()
	{
		return $this->ip;
	}
}

?>