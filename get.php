<?php

class fichier{
	private $ch;
	private $filename;
	private $pw;
	function login($user, $pass){
	$this->ch = curl_init();
	curl_setopt($this->ch, CURLOPT_POST, TRUE);
	curl_setopt($this->ch, CURLOPT_POSTFIELDS, ["lt" => "on", "mail" => $user, "pass" => $pass, "purge" => "on", "valider" => "Send"]);
	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($this->ch , CURLOPT_URL, "https://1fichier.com/login.pl");
	curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($this->ch , CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($this->ch, CURLOPT_COOKIEJAR, "fich");  //could be empty, but cause problems on some hosts
	curl_setopt($this->ch, CURLOPT_COOKIEFILE, "fich");  //could be empty, but cause problems on some hosts
	curl_exec($this->ch);
	}
	function setpw($pw){ $this->pw = $pw;}
	function curlHeaderCallback($resURL, $strHeader) {
		if(preg_match("/filename/", $strHeader)){
		$f = explode("filename=\"", $strHeader);
		$f = explode("\"; filename", $f[1]);
		$this->filename = $f[0];
		}
   return strlen($strHeader);
	} 
	function get_filename($url){
		curl_setopt($this->ch , CURLOPT_URL, $url);
		curl_setopt($this->ch , CURLOPT_HEADERFUNCTION, array(&$this,'curlHeaderCallback')); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, ["did" => "0", "dl_no_ssl" => "on", "pass" =>  $this->pw]);
		curl_setopt($this->ch,CURLOPT_TIMEOUT,1);
		curl_exec($this->ch);
	}
	function get_file($url){
		$this->get_filename($url);
		curl_setopt($this->ch , CURLOPT_URL, $url);
		curl_setopt($this->ch , CURLOPT_HEADERFUNCTION, array(&$this,'curlHeaderCallback')); 
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, ["did" => "0", "dl_no_ssl" => "on", "pass" =>  $this->pw]);
		curl_setopt($this->ch,CURLOPT_TIMEOUT,-1);
		curl_setopt($this->ch, CURLOPT_FILE, fopen($this->filename, 'w+')); 
		curl_exec($this->ch);
	}		
}
$f = new fichier();
$f->login("user", "pass");
$f->setpw("filepw");
$f->get_file("url");

?>
