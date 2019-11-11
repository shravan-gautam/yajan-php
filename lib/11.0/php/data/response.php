<?php
class Response
{
    var $data;
    function __construct($type="",$text="")
    {
       $this->data = array();
       $this->data["type"]="";
       $this->data["text"]="";
        if($type!="")
        {
            $this->data["type"]=$type;
        }
        if($text!="")
        {
            $this->data["text"]=$text;
        }
    }
    function set($type,$text)
    {
        $this->data["type"]=$type;
        $this->data["text"]=$text;
    }
    function add($key,$data)
    {
        $this->data[$key]= $data;
    }
    function __toString()
    {
        return json_encode($this->data);
    }
}
?>