<?php

namespace arabcci_chamber_key;

class encryptionInfo
{
    private $_keyLength;
    private $_arabcci_key;
    private $_cipher;
    private $_iv;
    private $_sourceURL;
    private $_userURL;
    public function __construct($name, $password, $userURL)
    {
        //encritption parameter
        $this->_keyLength=16;
        $this->_arabcci_key= openssl_random_pseudo_bytes($this->_keyLength, $keyStrong);
        $this->_cipher= "AES-192-CFB"; //encription method
        $this->_iv = openssl_random_pseudo_bytes($this->_keyLength, $ivStrong);

        //user enter information
        $this->_enteredName=$name;
        $this->_enteredPassword=$password;
        //user's sourceURL
        $this->_userURL= $userURL;

        //permited sourceURL
        $this->_sourceURL='http://localhost/eshop/admin/CMSlogin.html';
    }
    private function checkHTTP_Referer()
    {
        if (!empty($this->_userURL) && $this->_userURL==$this->_sourceURL) {
            //AJAX Request from own server
        }
    }
    private function encryptedInfo()
    {
        $enInfo=openssl_encrypt($this->_enteredName, $this->_cipher, $this->_arabcci_key, 0, $this->_iv);
        return $enInfo;
        
        
        /*
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secretHash, 0, $iv);
            $decryptedMessage = openssl_decrypt($encryptedMessage, $encryptionMethod, $secretHash, 0, $iv);
             */
    }
    private function decryptedInfo()
    {
        $enInfo =$this->encryptedEnteredName();
        $deInfo = openssl_decrypt($enInfo, $this->_cipher, $this->_arabcci_key, 0, $this->_iv);
        return  $deInfo;
    }
    
    public function testEcho()
    {
        $enEnteredName= $this->encryptedInfo();
        $deEnteredName= $this->decryptedInfo();
        echo "encrypted:".$enEnteredName."<br/>\n";
        echo "decrypted:".$deEnteredName;
    }
}