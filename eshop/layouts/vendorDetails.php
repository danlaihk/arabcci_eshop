<?php
include_once('../library/php/eShopClass.php');

use arabcci_chamber_eshop\vendor;
use arabcci_chamber_eshop\product;
use function arabcci_chamber_eshop\queryShopDB_PDO;

////// used the relative path from categoriesList.php to loadProductsInfo.php


    ////get ajax queries value

 
    $code = $_REQUEST["vendor"];
    
    $vendor = new vendor($code);
  

 
?>

<div class="row fade-in3 mt-4">
    <div class="col-md-12">
        <!-- first row of product details-->
        <div class="row px-2">
            <div class="col-md float-left pl-4">
                <h5 class="pl-2">
                    <span class="font-weight-bold">
                        Vendor
                    </span>
                </h5>




            </div>
            <div class=" col-md float-right text-right">
                <!-- path of products-->
                <a href="shop.php">Shop</a>
                <i class="fas fa-angle-double-right bg-transparent"></i>
                <?php
               
                    echo "<a href='#' onclick=\"loadDoc('layouts/vendorDetails.php?vendor=".$code."', loadContent,'main')\">";
                  
                    echo $vendor->getVenName();

                    echo "</a>";

                ?>


            </div>

        </div>

        <!-- second row of product details-->
        <div class="row mt-3">
            <div class="col-md-8 bg-white">


                <!-- Product Image-->

                <div class="row pt-3 pl-3">
                    <div class="col-md-12 pl-4 ">
                        <?php
                      
                        //print image url
                        echo '<img class="w-100" src="'.$vendor->getVenImage().'" />';
                        ?>

                    </div>
                </div>





            </div>

            <!-- Vendor info-->
            <div class="col-md-4 ">
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">
                        Vendor Details: &nbsp;&nbsp;&nbsp;
                        <?php
                       

                        //print vendor name and ajax queries
                        echo "<a href='#' onclick=\"loadDoc('layouts/vendorDetails.php?vendorName=".$code."',loadContent,'main')\">\n";
                        echo $vendor->getVenName();
                        echo "</a>";
                        ?>
                    </div>
                </div>
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">
                        <h6>Description:</h6>
                        <p>
                            <?php
                            echo $vendor->getVenDescription();
                            ?>
                        </p>



                    </div>
                </div>
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">
                        <h6>Vendor link &nbsp;&nbsp;
                            <small>
                                <?php
                                if ($vendor->getVenHtml()===null) {
                                    echo 'N/A';
                                } else {
                                    echo $vendor->getVenHtml();
                                }
                                ?>
                            </small>
                        </h6>

                    </div>
                </div>


                <div class="row pl-4">
                    <div class="col-md-12 bg-white   pl-3 pr-4">

                        <!--share vendor button -->
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
                                if ($vendor->getVenHtml()!=null) {
                                    echo '<a class="social-media-link"
                                href="https://www.facebook.com/sharer/sharer.php?u='.$vendor->getVenHtml().'">';
                                } else {
                                    echo 'N/A';
                                }
                                ?>



                                <span class="fa-stack fa-1x">
                                    <?php
                                    if ($vendor->getVenHtml()!=null) {
                                        echo'
                                    <i class="fas fa-square fa-stack-2x"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>';
                                    }
                                    ?>
                                </span>

                                </a>
                                <!-- Twitter sharing buttons-->

                                <?php
                                //twitter share link
                                //domain need to change after deploy on server
                                if ($vendor->getVenHtml()!=null) {
                                    echo '<a class="twitter-share-button social-media-link"
                                    href="https://twitter.com/intent/tweet?url='.$vendor->getVenHtml().'"
                                    data-size="large">';
                                }
                               
                                ?>

                                <span class="fa-stack fa-1x">
                                    <?php
                                    if ($vendor->getVenHtml()!=null) {
                                        echo'
                                    <i class="fas fa-square fa-stack-2x"></i>
                                    <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>';
                                    }
                                    ?>
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
                <div class="row px-4 pt-3">
                    <div class="col-md-12">
                        <h5>
                            <?php
                            $sql = "SELECT * FROM `products` WHERE `vendorName`=?";
                                            
                            $shopProductArr=queryShopDB_PDO($sql, $vendor->getVenName());
                            
                            if (count($shopProductArr)>=1) {
                                echo $vendor->getVenName().' products';
                            }
                                
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="row px-4 pt-3 ">
                    <?php
                    //print product with same vendor
                       
                    if (count($shopProductArr)>=1) {
                        // for loop to the shop products
                        for ($i=0;$i<count($shopProductArr);$i++) {
                            echo '<!-- col for each -->';
                            echo '<div class="col-sm-3 px-4  pb-5">'."\n";
                            //print product cards
                                
                            //declare product object
                            $product = new product($shopProductArr[$i]['productCode']);
                            $product->printProductCard();


                            echo '</div>';
                        }
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>
</div>