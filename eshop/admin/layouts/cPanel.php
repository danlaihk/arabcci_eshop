<?php


if (isset($_REQUEST['userName'])==false ||isset($_REQUEST['password'])==false||isset($_SERVER['HTTP_REFERER'])==false||isset($_REQUEST['token'])==false) {
    header("Location: http://localhost/eshop/admin/CMSlogin.php");
    die();
}
//print the body part of cpanel
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 bg-dark">
            <h2 class="text-white">Control Panel</h2>
        </div>
    </div>
</div>