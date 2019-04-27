<?php

namespace Arabcci_Chamber_Login;

use PDO;

class AdminCheck_DBInfo
{
    private $_servername = "localhost";
    private $_username = "adminUserCheck";
    private $_password = "5L6ZlQuZSH3IIMkH";
    private $_dbname = "admin_user_login";
    private $_sql;
   
    public function __construct($sql)
    {
        $this->_sql=$sql;
    }
    
    public function queryUserDB_PDO()
    {
        $servername = $this->_servername;
        $dbname   = $this->_dbname;
        $username = $this->_username;
        $password = $this->_password;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            $stmt = $conn->prepare($this->_sql);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getUserPW()
    {
        //use PDO class
        $result = $this->queryUserDB_PDO();
        if (count($result)>0) {
            foreach ($result as $row) {
                echo $row."<br/>";
            }
        } else {
            echo 'sorry not record';
        }
    }
    
    //mysql method
    private function connectShopDB()
    {
        // Create connection
        $conn = new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            return $conn;
        }
    }
    public function getAuthenticationResult()
    {
        $conn= $this->connectShopDB();
        $sqlResultArr= array();

        $result=$conn->query($this->_sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                array_push($sqlResultArr, $row);
            }
           
            /////else return a empty array
        }

        $conn->close();
        return $sqlResultArr;
    }
}
class LoginInfo
{
    //user infomation
    private $_enteredName;
    private $_enteredPassword;
    private $_sourceURL;
    private $_token;
    //permited sourceURL
    private $_userURL;

    public function __construct($name, $password, $userURL, $token)
    {


        //user information, may use or delete
        $this->_enteredName=$name;
        $this->_enteredPassword=$password;

        //user's sourceURL
        $this->_userURL= $userURL;
        
        //http request token
        $this->_token=$token;

        //permited sourceURL
        $this->_sourceURL='http://localhost/eshop/admin/CMSlogin.php';
    }
    public function getEnterUserName()
    {
        return $this->_enteredName;
    }
    public function checkHTTP_Referer()
    {
        //check user source url
        if (!empty($this->_userURL) && $this->_userURL==$this->_sourceURL) {
            return true;
        } else {
            return false;
        }
    }
    public function checkCallType()
    {
        //check user call type
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
    public function tokenCheck()
    {
        session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        //the header of return text to login page
        //header('Content-Type: application/json');

        //the available of reuqest header['CsrfToken'] is checked outside
        if ($this->_token !== $_SESSION['csrf_token']) {
            //exit(json_encode(['error' => 'Wrong token.']));
            echo 'wrong token';
            exit();
        }
    }
    //may call encryption session
}
//encryption class
class EncryptionSession
{
    //encryption method parameters
    private $_keyLength;
    private $_arabcci_key;
    private $_cipher;
    private $_iv;

    //user info
    private $_userName;
    private $_password;
    public function __construct($userName, $password)
    {
        //encritption method parameter
        $this->_keyLength=16;
        $this->_arabcci_key= openssl_random_pseudo_bytes($this->_keyLength, $keyStrong);
        $this->_cipher= "AES-192-CFB"; //encription method
        $this->_iv = openssl_random_pseudo_bytes($this->_keyLength, $ivStrong);
    }
    private function encryptedInfo($info)
    {
        //may need to change

        $encryptedInfo=openssl_encrypt($info, $this->_cipher, $this->_arabcci_key, 0, $this->_iv);
        return $encryptedInfo;
    }
    private function decryptedInfo($encryptedInfo)
    {
        //may need to change
        $decryptedInfo = openssl_decrypt($encryptedInfo, $this->_cipher, $this->_arabcci_key, 0, $this->_iv);
        return $decryptedInfo;
    }
}
//hashing class
class HashSession
{
    private $_plainText;
    private $_hashText;
    public function __construct($plainText)
    {
        $this->_plainText=$plainText;
    }
    private function hashingText()
    {
        $this->_hashText = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
    }
    public function getHashText()
    {
        return $this->_hashText;
    }
}

class VerifyHashSession
{
    private $_hashText;
    private $_plainText;
    public function __construct($hashText, $plainText)
    {
        $this->_hashText=$hashText;
        $this->_plainText=$plainText;
    }
    public function verifyHash()
    {
        if (password_verify($this->_plainText, $this->_hashText)) {
            return true;
        } else {
            return false;
        }
    }
}