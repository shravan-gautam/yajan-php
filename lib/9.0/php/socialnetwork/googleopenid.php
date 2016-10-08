<?php
require_once("$LIB_PATH/php/socialnetwork/google/openid.php");
class GoogleOpenid
{
	var $openid;
	function GoogleOpenid($host)
	{
		$this->openid = new LightOpenID($host);
	}
	function login()
	{
		if ($this->openid->mode) {
			if ($this->openid->mode == 'cancel') {
				echo "User has canceled authentication!";
			} elseif($this->openid->validate()) {
				$data = $this->openid->getAttributes();
				$email = $data['contact/email'];
				$first = $data['namePerson/first'];
				echo "Identity: $this->openid->identity <br>";
				echo "Email: $email <br>";
				echo "First name: $first";
			} else {
				echo "The user has not logged in";
			}
		} else {
			echo "Go to index page to log in.";
		}
	}
	function form()
	{
		$this->openid->identity = 'https://www.google.com/accounts/o8/id';
		$this->openid->required = array(
		  'namePerson/first',
		  'namePerson/last',
		  'contact/email',
		);
		$this->openid->returnUrl = 'http://erp.awgp.in/login.php';
	

		echo '<a href="'.$this->openid->authUrl().'">Login with Google</a>	';
	}
}
?>