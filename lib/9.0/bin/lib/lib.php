<?php
class ENC
{
    protected $key = "JUST A KEY";
    protected $cipher = "rijndael-256";
    protected $mode = "cbc";	
	function ENC()
	{
		
	}
	public function m_encrypt($data)
    {
        return (string) 
         (
          mcrypt_encrypt(
           $this->cipher,
           substr(md5($this->key),0,mcrypt_get_key_size($this->cipher, $this->mode)),
           $data,
           $this->mode,
           substr(md5($this->key),0,mcrypt_get_block_size($this->cipher, $this->mode))
          )
         );
    }
	public function m_decrypt($data)
    {
        return (string)
          mcrypt_decrypt(
           $this->cipher,
           substr(md5($this->key),0,mcrypt_get_key_size($this->cipher, $this->mode)),
           ($data),
           $this->mode,
           substr(md5($this->key),0,mcrypt_get_block_size($this->cipher, $this->mode))
          );
    }
	private function enc2_encode($str)
	{
		$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$key_size =  strlen($key);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $str, MCRYPT_MODE_CBC, $iv);
		return $iv . $ciphertext;
		
	}
	private function enc2_decode($str)
	{
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	}	
}
?>