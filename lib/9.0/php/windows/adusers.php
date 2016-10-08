<?php

class ADUsers
{
	var $server;
	function ADUsers(ADClient $server)
	{
		$this->server = $server;
	}
	function addUser($firstName, $lastName, $username, $pwdtxt, $email)
	{
		$localDn = "CN=$firstName $lastName,CN=Users";
		$newPassword = "\"" . $pwdtxt . "\"";
		$len = strlen($newPassword);
		$newPassw = "";
		for($i=0;$i<$len;$i++) {
			$newPassw .= "{$newPassword{$i}}\000";
		}
		$ldaprecord['cn'] = $firstName." ".$lastName;
		$ldaprecord['displayName'] = $firstName." ".$lastName;
		$ldaprecord['name'] = $firstName." ".$lastName;
		$ldaprecord['givenName'] = $firstName;
		$ldaprecord['sn'] = $lastName;
		$ldaprecord['mail'] = $email;
		$ldaprecord['objectclass'] = array("top","person","organizationalPerson","user");
		$ldaprecord["sAMAccountName"] = $username;
		//$ldaprecord["unicodepwd"] = $newPassw;
		$ldaprecord["UserAccountControl"] = "544";
		$this->server->add($localDn,$ldaprecord,false);
		$encodedPass = array('userpassword' => base64_encode($newPassw));
		return $this->server->update($localDn,$encodedPass,false);
	}
}

?>