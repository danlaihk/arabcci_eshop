<?php

include_once("arabcci_login.php");

use Arabcci_Chamber_Login\LoginInfo;
use Arabcci_Chamber_Login\AdminCheck_DBInfo;
use Arabcci_Chamber_Login\VerifyHashSession;
use Arabcci_Chamber_Login\EncryptionSession;

//use Arabcci_Chamber_Login\HashInfo;
//use Arabcci_Chamber_Login\AdminCheck_DBInfo;

//handle null query

//get http request header


    if (isset($_REQUEST['userName'])==false ||isset($_REQUEST['password'])==false||isset($_SERVER['HTTP_REFERER'])==false||isset($_REQUEST['token'])==false) {
        echo 'wrong http query';
        die();
    }

    //run coding

    //declare login info object
    $loginInfo=new LoginInfo($_REQUEST['userName'], $_REQUEST['password'], $_SERVER['HTTP_REFERER'], $_REQUEST['token']);

    //checking call type

    if ($loginInfo->checkCallType()==false) {
        exit();
    }

    //checking source url


    
    if ($loginInfo->checkHTTP_Referer()==false) {
        echo $_SERVER['HTTP_REFERER'];
        exit();
    }
    

    $loginInfo->tokenCheck();

    //checking token


    /********************************************************************* */
    #
    #test account:admin root, need to delete this comment before deployment
    #
    /********************************************************************* */
    
    //search the record of user
    //select *blablabla
    
    //create connection object
    $connInfo=new AdminCheck_DBInfo('ashop_userCheck', $_REQUEST['userName']);
    //get info
    $sql ="SELECT * FROM `authentication` WHERE userName=?";
    $result=$connInfo->queryDB_PDO($sql, $_REQUEST['userName']);

    $jsonObj= new stdClass();

    if (count($result)>0) {
        //get result
        //debug echo record after decrypted
        $password=$result[0]['password'];// only one result show be shown
    } else {
        //if result=0 handle error
        $jsonObj->{"correct"}=false;
 
        echo json_encode($jsonObj);
        die();
    }

    $hashVerify = new VerifyHashSession($password, $_REQUEST['password']);
    
    //check the password

    if ($hashVerify->verifyHash()===false) {
        $jsonObj->{"correct"}=false;
 
        echo json_encode($jsonObj);
    } else {
        $encryptSession = new EncryptionSession();
     
        $encryptInfo =$encryptSession->getEncryptedInfo($_REQUEST['userName']);

        $jsonObj->{"correct"}=true;
        $jsonObj->{"userName"}=$_REQUEST['userName'];
        $jsonObj->{"token"}=$encryptInfo;

        echo json_encode($jsonObj);
    }