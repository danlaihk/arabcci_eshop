<?php

include_once("encryption.php");
use arabcci_chamber_key\encryptionInfo;

//handle null query

if (isset($_REQUEST['userName'])==false ||isset($_REQUEST['password'])==false) {
    exit();
} else {
    //run coding

    //checking source url
   
    
    

    $encryptionInfo=new encryptionInfo($_REQUEST['userName'], $_REQUEST['password'], $_SERVER['HTTP_REFERER']);

    
    //encription username and password

    //debug echo encrypted
    //search the record of encrypted user
    //select *blablabla

    //get result
    //debug echo record after decrypted

    //if result=0 handle error

    //else check password and wrong login count and last trial time

    //if pass then redirect to CMS page
}
?>

<div class="row">
    <div class="col-12">
        <?php
    //$encryptionInfo->testEcho();
    echo $_SERVER['HTTP_REFERER'];
    ?>
    </div>

</div>