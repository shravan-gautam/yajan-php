<?php
class AccessSecurity
{
        var $access;
        var $private;
        var $message;
        var $sessionSecurity;
        var $sessoin;
        var $key;
        var $sessionVarification;
        function __construct($app,$class,$method,$defaultPrivate=true)
        {
                $this->sessionSecurity=false;
                $this->sessionVarification=false;
                if($defaultPrivate==true)
                {
                        $this->private=true;

                        $filename = "var/publicAccess.list";
                        $this->access = "$app/$class/$method";
                        if(file_exists($filename))
                        {
                                $cmd = "grep '$this->access' $filename | wc -l";
                                exec($cmd,$output);
                                if($output[0]=="0")
                                {
                                        $this->private=true;
                                }
                                else
                                {
                                        $this->private=false;
                                }
                        }
                        else
                        {
                                $this->private=true;
                        }
                }
                else
                {
                        $this->private=false;

                        $filename = "var/privateAccess.list";
                        $this->access = "$app/$class/$method";
                        if(file_exists($filename))
                        {
                                $cmd = "grep '$this->access' $filename | wc -l";
                                exec($cmd,$output);
                                if($output[0]=="0")
                                {
                                        $this->private=false;
                                }
                                else
                                {
                                        $this->private=true;
                                }
                        }
                        else
                        {
                                $this->private=false;
                        }
                }
        }
        function setSession($session)
        {
                $this->session=$session;
                $this->sessionSecurity=true;
        }

        function isValid()
        {
                if($this->private==false)
                {
                        return true;
                }
                $i=0;
                $salt="";
                $k=$_REQUEST["key"];
                $hash=$_REQUEST["hash"];
                $this->key = $k;
                foreach ($_REQUEST as $key => $value)
                {
                        if($i>0)
                        {
                                if($key!="hash")
                                {
                                        $data.=$value;
                                }
                        }
                        $i++;
                }

                $k = new APIAccessKey($k);
                $salt=$k->getSalt();
                if($salt=="")
                {
                        $this->message="Key not valid";
                        return false;
                }

                $data.=$salt;
                //echo $data."\n";
                $md = md5($data);
                //echo $hash."..".$md."\n";
                if($hash!=$md)
                {
                        $this->message="Key,Hash or data is invalid.";
                        return false;
                }
                if($this->sessionSecurity)
                {
                        if(!isset($_REQUEST["session"]))
                        {
                                $this->message = "Session variable not avilable";
                                return false;
                        }

                        if($this->sessionVarification == true && $this->session!=$_REQUEST["session"])
                        {
                                $this->message = "Invalid session";
                                return false;
                        }
                }
                return true;
        }
}
?>