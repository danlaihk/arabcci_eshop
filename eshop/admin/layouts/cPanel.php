<?php


include_once('../library/php/cPanelControl.php');


loadCMSIndex();

?>

<!--
<!DOCTYPE html>
<html>
-->
<!--js for cPanel-->
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <!-- block search engine-->
    <meta name="robots" content="noindex, nofollow">
    <!-- turn responsive on/off-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
    <meta name="description" content="Eshop of Hong Kong Arabcci Chamber">


    <title>Arabcci CMS</title>

    <!-- Jquery -->
    <script src="../library/Jquery_331/jquery-3.3.1.slim.min.js">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
    <script src="../library/popperjs/popper_1.14.7.min.js"></script>

    <script src="../library/bootstrap_431/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../library/bootstrap_431/css/bootstrap.min.css">

    <!-- Font Awesome library -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Custom styles for this template -->



    <!-- Custom javascript for this template -->
    <script src="../library/js/loginClass.js"></script>


    <script src="../library/js/cPanel.js"></script>

</head>

<script>
$(document).ready(function() {
    //set fix height of side menu
    var winHeight = $(window).height();
    $("#sideMenuContainer").height(winHeight * 0.9);

    $("#btnLogout").click(function() {
        logoutPanel();
    });
});
</script>


<div class="container-fluid">
    <div class="row">
        <div class="col-12 bg-dark">
            <p>
                <span class="text-white h2 float-left">Control Panel</span>
                <span class="text-white float-right">Welcome,
                    <?php echo $_SESSION['userName']?> !!<br>
                    <button id="btnLogout" class="btn btn-sm btn-primary float-right my-2">logout</button>
                </span>

            </p>


        </div>
    </div>


    <div class="row ">
        <!--side menu-->
        <div class="col-2  bg-secondary">
            <div class="row pt-4">
                <div id="sideMenuContainer" class="col-12  ">

                    <div class="list-group">
                        <a href="cPanel.php" class="list-group-item border-0 bg-transparent text-white" onclick=""
                            href="#">index</a>
                        <a class="list-group-item border-0 bg-transparent text-white"
                            onclick="loadSubCMS('product','<?php echo $_SESSION['userName'];?>')" href="#">Product</a>



                        <a class="list-group-item border-0 bg-transparent text-white" onclick="" href="#">Featured</a>



                        <a class="list-group-item border-0 bg-transparent text-white" onclick="" href="#">Vendor</a>

                    </div>
                </div>
            </div>
        </div>

        <!--cms content-->
        <div class="col-10 " id="cmsContent">

            <h4 class="pt-4 pl-3"> Products</h4>

            <div class="col-12 overflow-auto">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Categories</th>
                            <th>Vendor</th>
                            <th>Description</th>
                            <th>Total Stock</th>
                            <th>Buy price</th>
                            <th>MSRP</th>
                            <th>Discount</th>
                            <th>Attributes</th>
                            <th>Image</th>
                            <th>Hit Rate</th>
                        </tr>
                    </thead>
                    <?php echo printPListHTML();?>
                </table>
            </div>
        </div>
    </div>
</div>


</html>