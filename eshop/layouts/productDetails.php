<?php

    ////// used the relative path from categoriesList.php to loadProductsInfo.php
    include_once('../library/php/loadProductsInfo.php');

    ////get ajax queries value
    $pID = $_REQUEST["product"];
     /////echo $cat;

    //get product information first
    $sql="SELECT * FROM products WHERE productCode = '".$pID."'";
    $pInfoArr=getSQLResult($sql);
    

    //get the product line name of categorie
    $sql = "SELECT productLine FROM categories WHERE categoriesName = '".$pInfoArr[0]['categoriesName']."'";
                    
    $catInfoArr=getSQLResult($sql);
?>

<div class="row fade-in3 mt-4">
    <div class="col-md-12">
        <!-- first row of product details-->
        <div class="row px-2">
            <div class="col-md float-left pl-4">

                <?php
                    //print product's categories Name
                    echo '<h5 class="pl-2">'."\n";
                    echo '<span class="font-weight-bold">'."\n";
                    echo $pInfoArr[0]['categoriesName']."\n";
                    echo '</span>'."\n";
                    echo '</h5>'."\n";
                ?>



            </div>
            <div class=" col-md float-right text-right">
                <!-- path of products-->
                <a href="shop.php">Shop</a>
                <i class="fas fa-angle-double-right bg-transparent"></i>
                <?php
                    
                    echo "<a href='#' onclick=\"loadDoc('layouts/productLine.php?pLine=".$catInfoArr[0]['productLine']."', loadContent,'main')\">";
                  
                    echo $catInfoArr[0]['productLine'];

                    echo "</a>";

                ?>

                <i class="fas fa-angle-double-right bg-transparent"></i>

                <?php
                    echo "<a href='#' onclick=\"loadDoc('layouts/categoriesList.php?cat=".$pInfoArr[0]['categoriesName']."',loadContent,'main')\">";
                    echo $pInfoArr[0]['categoriesName'];
                    echo "</a>";
                ?>
                <i class="fas fa-angle-double-right bg-transparent"></i>

                <?php
                    echo "<a href='#' onclick=\"loadDoc('layouts/productDetails.php?product=".$pInfoArr[0]['productCode']."',loadContent,'main')\">";
                    echo $pInfoArr[0]['productName'];
                    echo "</a>";
                ?>
            </div>

        </div>

        <!-- second row of product details-->
        <div class="row mt-3">
            <div class="col-md-8 bg-white">
                <div class="row pt-4 pl-3">
                    <div class="col-md-12  pl-4 ">
                        <h5>
                            <?php
                            // print product name
                            echo $pInfoArr[0]['productName']."\n";
                            ?>
                        </h5>
                    </div>
                </div>

                <!-- Product Image-->

                <div class="row pt-3 pl-3">
                    <div class="col-md-12 pl-4 ">
                        <?php
                        //print image url
                        echo '<img class="w-100" src="'.$pInfoArr[0]['Image'].'" />';
                        ?>

                    </div>
                </div>

                <div class="row pt-3 pl-3">
                    <div class="col-md-12 pl-4 ">
                        <h5><span class="border-bottom border-secondary">Product Description</span></h5>
                        <p class="my-3">
                            <?php
                            // product description

                            echo $pInfoArr[0]['productDescription'];
                            ?>
                        </p>

                    </div>
                </div>



            </div>

            <!-- price info-->
            <div class="col-md-4 ">
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">


                        <?php
                                if ($pInfoArr[0]['discount(%)']===100) {
                                } else {
                                    echo '<span class="text-secondary mr-5">'."\n";
                                    echo '<span class="text-dark"><strong>Original Price: </strong></span>';
                                    echo '<del>';
                                    echo $pInfoArr[0]['MSRP'];
                                    echo '</del>'."\n";
                                    echo '</span>'."\n";

                                    echo '<span class="text-success">'."\n".
                                    "<strong>"."\n ";
                                    echo '<span class="text-dark">Now :'."\n ";
                                    echo '</span>'."\n ";
                        
                                    echo $pInfoArr[0]['MSRP']*$pInfoArr[0]['discount(%)']/100;
                               
                                    echo '</strong>'."\n".
                                    "</span>\n";
                                }
                            ?>


                    </div>
                </div>
                <div class="row pl-4">
                    <div class="col-md-12 bg-white  py-3 pl-3 pr-4">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- generate a ajax query to checkout.php-->
                                <button class="btn btn-lg btn-primary w-100" onclick="">Buy Now!</button>
                            </div>

                        </div>
                        <div class="row pt-4">
                            <div class="col-md-12 pb-3">
                                Shared With Friends
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- share button codes-->

                                <!-- facebook sharing buttons-->
                                <!-- cannot share localhost link-->

                                <?php
                                //facebook share link
                                //domain need to change after deploy on server
                                echo '<a class="social-media-link"
                                href="https://www.facebook.com/sharer/sharer.php?u=http://hkislamicindex.com/productDetails?product='.$pInfoArr[0]['productCode'].'">';
                                ?>



                                <span class="fa-stack fa-1x">
                                    <i class="fas fa-square fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                </span>

                                </a>
                                <!-- Twitter sharing buttons-->

                                <?php
                                //twitter share link
                                //domain need to change after deploy on server
                                echo '<a class="twitter-share-button social-media-link"
                                    href="https://twitter.com/intent/tweet?url=http://hkislamicindex.com/productDetails?product='.$pInfoArr[0]['productCode'].'"
                                    data-size="large">';
                                ?>

                                <span class="fa-stack fa-1x">
                                    <i class="fas fa-square fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                                </a>




                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        <!-- third row of product details same categories product-->
        <div class="row mt-3 mb-5">
            <div class="col-md-12">
                <div class="row px-4">
                    <div class="col-md-12">
                        <h5>
                            <?php
                                echo 'More '.$pInfoArr[0]['vendorName'].' products';
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="row px-4 pt-3 ">
                    <?php
                    //print product with same vendor
                            $sql = "SELECT * FROM `products` WHERE `vendorName`='".$pInfoArr[0]['vendorName']."'";
                    
                            $shopProductArr=getSQLResult($sql);

                             printShopProductsRow($shopProductArr, 4, $pInfoArr[0]['productCode']);
                        ?>
                </div>

                <div class="row px-4">
                    <div class="col-md-12">
                        <h5>
                            You may also interest in...
                        </h5>
                    </div>
                </div>

                <div class="row px-4 pt-3 ">

                    <?php
                    //print product with same category
                            $sql = "SELECT * FROM `products` WHERE `categoriesName`='".$pInfoArr[0]['categoriesName']."'";
                    
                            $shopProductArr=getSQLResult($sql);

                             printShopProductsRow($shopProductArr, 4, $pInfoArr[0]['productCode']);
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>