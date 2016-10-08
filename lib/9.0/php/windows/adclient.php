<?php
class ADClient
{
	var $server;
	var $connection;
	var $message;
	var $error;
	var $domain;
	var $baseDn;
	var $queryResult;
	var $searchDn;
	var $searchFilter;
	function ADClient($domain,$server,$user,$pass)
	{
		$this->error = "";
		$this->server = $server;
		$this->domain = $domain;
		$this->connection = ldap_connect($this->server);
		$this->baseDn = "DC=".join(',DC=', explode('.', $this->domain));
		if (!$this->connection)
		{
			$this->error = 'W001';
			$this->message = 'Could not connect to LDAP server';
			return;
		}				
		ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
		$bind = @ldap_bind($this->connection, $user."@".$this->domain, $pass);
		define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);
		
		ldap_get_option($this->connection, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
		if (!empty($extended_error))
		{
			//echo $extended_error;
			$this->error = explode(',', $extended_error);
			$this->error = $errno[2];
			$this->error = explode(' ', $this->error);
			$this->error = $errno[2];
			$this->error = intval($this->error);

			if ($errno == 532)
			{
					$this->message = 'Unable to login: Password expired';
					return;
			}
			else if($errno=="52e")
			{
					$this->message = 'Invalid login credentials.';
					return;
			}
		}
		elseif (!$bind)
        {
			$this->message = 'Unable to bind to server';
			return;
		}
	}
	function getMembers($objects,$group=false,$fields=null)
	{
		if(is_array($group))
		{
			$str = "";
			for($i=0;$i<count($group);$i++)
			{
				$gr = $group[$i];
				$str.="(memberOf=$gr,$this->baseDn)";
			}
			$group = "(&(|$str))";
		}
		else
		{
			$group = "(&(memberOf=$group,$this->baseDn))";
		}
		$this->query("CN=$objects,$this->baseDn",$group,false);
		return $this->getQueryResultRecordset($fields);
	}
	function query($query,$filter="(CN=*)",$mode=true)
	{
		if($mode==false)
		{
			$dn = $query;
		}
		else
		{
			$dn = $query.",".$this->baseDn;
			if($filter!="(CN=*)")
			{
				$filter = $filter.",".$this->baseDn;
			}
		}
		$this->searchDn=$dn;
		$this->searchFilter=$filter;
		$result = ldap_search(array($this->connection), $dn, $filter);
		if (!count($result))
		{
			$this->message = 'Unable to login: '. ldap_error($this->connection);
			$this->error="W002";
		}
		$this->queryResult = array();
		foreach ($result as $res)
		{
			$this->queryResult[count($this->queryResult)] = ldap_get_entries($this->connection, $res);
		}
		return $this->queryResult;
	}
	function getColumns()
	{
		$rows = count($this->queryResult[0]);
		$cols = array();
		for($i=0;$i<$rows;$i++)
		{
			if(isset( $this->queryResult[0][$i]))
			{
				$obj = $this->queryResult[0][$i];
				foreach($obj as $k=>$v)
				{
					if(is_numeric($k))
					{
						if(array_search($v,$cols)===false)
						{
							
							$cols[count($cols)]=$v;
						}
					}
					else
					{
						if(array_search($k,$cols)===false)
						{
							$cols[count($cols)]=$k;
						}
					}
				}
			}
			
		}
		return $cols;
	}
	function getQueryResultRecordset(array $fields=null)
	{
		return $this->getQueryReeultRecordset($fields);
	}
	function getQueryReeultRecordset(array $fields=null)
	{
		if($fields==null)
		{
			$fields = $this->getColumns();
		}
		$fs = $fields;
		$count = $this->queryResult[0]['count'];
		$r = new Recordset();
		if($fields!=null)
		{
			$fields = array_map('strtoupper', $fields);
		}
		for($j=0;$j<count($fields);$j++)
		{
			$r->addColumns($fields[$j]);
		}
		for($i=0;$i<$count;$i++)
		{
			$obj = $this->queryResult[0][$i];
			
			$cn = $obj['count'];
			$row = array();
			for($j=0;$j<count($fields);$j++)
			{
				$f = strtoupper($fields[$j]);
				if(isset($obj[$fs[$j]][0]))
				{
					$row[$f]=$obj[$fs[$j]][0];
				}
				else
				{
					$row[$f]="";
				}
			}
			$r->add($row);
		}
		return $r;
	}
	function execute($query,$fields=null,$filter="(CN=*)",$mode=true)
	{
		
		$r = $this->query($query,$filter,$mode);
		
		return $this->getQueryReeultRecordset($fields);
	}
	function getQueryDn($index=0)
	{
		return $this->queryResult[0][$index]['distinguishedname'][0];
	}
	function add($dn,$ldaprecord,$fqdn=true)
	{
		if(!$fqdn)
		{
			$dn = $query.",".$this->baseDn;
		}
		return ldap_add($this->connection, $dn, $ldaprecord);
	}
	function update($dn,$ldaprecord,$fqdn=true)
	{
		if(!$fqdn)
		{
			$dn = $query.",".$this->baseDn;
		}
		return ldap_mod_replace($this->connection, $dn, $ldaprecord);
	}
}
?>