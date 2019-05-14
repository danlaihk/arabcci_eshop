<?php

namespace Arabcci_Chamber_Login;

use PDO;

class AdminCheck_DBInfo
{
    private $_servername ;
    private $_dbUsername;
    private $_password;
    private $_dbname;
    private $_dbCode;
  
    
    public function __construct($code)
    {
        $this->_dbCode=$code;
        
        if ($this->_dbCode=='ashop_userCheck') {
            $this->_servername = "localhost";
            $this->_dbUsername = "userCheck";
            $this->_password = "check";
            $this->_dbname = "admin_user_login";
        }
        if ($this->_dbCode=='ashop') {
            $this->_servername = "localhost";
            $this->_dbUsername = "shop";
            $this->_password = "hYCIkdc2RDghvxAH";
            $this->_dbname = "arabcci_shop";
        }
    }
    public function queryDB_PDO($sql, $value)
    {
        $servername = $this->_servername;
        $dbname   = $this->_dbname;
        $dbUserName = $this->_dbUsername;
        $password = $this->_password;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUserName, $password);
        

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //echo "Connected successfully"; //debug
            $stmt = $conn->prepare($sql);
            if ($value==null) {
                // if no parameterized value
                $stmt->execute();
            } elseif (is_array($value)) {
                //for IN query and parameterized array value
                $stmt->execute($value);
            } else {
                //for parameterized value
                $stmt->execute([$value]);
            }
            $result= $stmt->fetchAll(\PDO::FETCH_ASSOC);
           
            //close connection
            $conn=null;
            //return result array
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
   
    public function alterDB_PDO($sql, $value)
    {
        $servername = $this->_servername;
        $dbname   = $this->_dbname;
        $dbUserName = $this->_dbUsername;
        $password = $this->_password;

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUserName, $password);
        

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //echo "Connected successfully"; //debug
            $stmt = $conn->prepare($sql);
            if ($value==null) {
                // if no parameterized value
                $stmt->execute();
            } elseif (is_array($value)) {
                //for IN query and parameterized array value
                $stmt->execute($value);
            } else {
                //for parameterized value
                $stmt->execute([$value]);
            }
        
            //$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $result=$stmt->rowCount();
            //close connection
            $conn=null;
            //return result array
            if ($result>0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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
        $this->_sourceURL="http://localhost/eshop/admin/CMSlogin.php";
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
    private $_userName;
    //user info

    public function __construct()
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

        $encryptedText=openssl_encrypt($info, $this->_cipher, $this->_arabcci_key, 0, $this->_iv);
        
        
        return $encryptedText;
    }
    public function decryptedInfo($encryptedText, $key, $iv)
    {
        //may need to change
        $decryptedText = openssl_decrypt($encryptedText, $this->_cipher, $key, 0, $iv);
        return $decryptedText;
    }
    public function getEncryptedInfo($userName)
    {
        do {
            //random generate a token
            $token = openssl_random_pseudo_bytes(8, $keyStrong);

            //search any duplicate token in token table
            $sql="SELECT * FROM token WHERE tokenValue = ?";
            $adminCheck = new AdminCheck_DBInfo('ashop_userCheck', $this->_userName);
            $result =$adminCheck->queryDB_PDO($sql, $token);
        } while ($result);  //while there is duplicate, re-generate and search again
        
        //then check if there any token exist for the userName
      
        $sqlToken="SELECT * FROM token WHERE userName = ?";
        $adminCheck = new AdminCheck_DBInfo('ashop_userCheck', $userName);
        $userNameResult =$adminCheck->queryDB_PDO($sqlToken, $userName);
        //debug

        
        //get current time
        $currentTime=date("Y-m-d H:i:s");
        $insertArr=array($userName,$token,$currentTime);
        //if no , insert

        if ($userNameResult) {
            //if yes,update
            $updateArr=array($token,$this->_arabcci_key,$this->_iv ,$currentTime,$userName);


            $sqlUpdate="UPDATE token SET tokenValue = ?,tokenKey = ?,tokenIV = ? ,createTime = ? WHERE userName =?";
            $changeTokenConn = new AdminCheck_DBInfo('ashop_userCheck', $userName);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlUpdate, $updateArr);
        } else {
            $insertArr=array($userName,$token,$this->_arabcci_key,$this->_iv,$currentTime);
            //if no , insert

            $sqlInsert="INSERT INTO token (userName, tokenValue, tokenKey, tokenIV, createTime) VALUES (?, ?, ?, ?, ?)";
            $changeTokenConn = new AdminCheck_DBInfo('ashop_userCheck', $userName);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlInsert, $insertArr);
        }
        
        //encrypted it and return the variable
        if ($changeResult===true) {
            $encryptInfo=$this->encryptedInfo($token);
            return $encryptInfo;
        }
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
        $plaintext=$this->_plainText;
        $this->_hashText = password_hash($plaintext, PASSWORD_DEFAULT);
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