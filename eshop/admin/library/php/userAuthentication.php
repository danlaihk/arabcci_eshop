<?php

include_once("arabcci_login.php");

use Arabcci_Chamber_Login\LoginInfo;
use Arabcci_Chamber_Login\AdminCheck_DBInfo;
use Arabcci_Chamber_Login\VerifyHashSession;

//use Arabcci_Chamber_Login\HashInfo;
//use Arabcci_Chamber_Login\AdminCheck_DBInfo;

//handle null query

//get http request header


if (isset($_REQUEST['userName'])==false ||isset($_REQUEST['password'])==false||isset($_SERVER['HTTP_REFERER'])==false||isset($_REQUEST['token'])==false) {
    echo 'wrong http query';
    exit();
} else {
    //run coding
   
    //declare login info object
    $loginInfo=new LoginInfo($_REQUEST['userName'], $_REQUEST['password'], $_SERVER['HTTP_REFERER'], $_REQUEST['token']);

    //checking call type
    
    if ($loginInfo->checkCallType()==false) {
        exit();
    }

    //checking source url
    //bug
    //echo $_SERVER['HTTP_REFERER'];
    
    if ($loginInfo->checkHTTP_Referer()==false) {
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
    $sql="SELECT password FROM authentication WHERE userName= '".$loginInfo->getEnterUserName()."'";
    $connObj=new AdminCheck_DBInfo($sql);
    $result=$connObj->queryUserDB_PDO();
    if (count($result)>0) {
        $password=$result[0]['password'];// only one result show be shown
    } else {
        echo 'sorry, no records';
    }
    $hashVerify = new VerifyHashSession($password, $_REQUEST['password']);
    if ($hashVerify->verifyHash()===true) {
        echo 'login';
    } else {
        echo 'wrong password';
    }
    
    
    //get result
    //debug echo record after decrypted

    //if result=0 handle error

    //else check password and wrong login count and last trial time

    //if pass then redirect to CMS page
}

?>
<div>

    <?php
    echo 'ok';
    ?>
</div>