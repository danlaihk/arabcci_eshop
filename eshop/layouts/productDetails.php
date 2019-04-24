<?php

    ////// used the relative path from categoriesList.php to loadProductsInfo.php
    include_once('../library/php/loadProductsInfo.php');
  
    include_once('../library/php/eShopClass.php');
    
    use arabcci_chamber_eshop\vendor;
    use arabcci_chamber_eshop\product;
    use arabcci_chamber_eshop\categories;
    use arabcci_chamber_eshop\productLine;

    ////get ajax queries value
    $pID = $_REQUEST["product"];
     

    $product= new product($pID);
    $categories= new categories($product->getPCat());
    $productLine =new productLine($categories->getCatPLine());

    $sql = "SELECT `vendorCode` FROM `vendors` WHERE `vendorName` = '".$product->getPVendor()."'";
    $resultArr= getSQLResult($sql);
    
    $vendor= new vendor($resultArr[0]['vendorCode']);

    

   
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
                    echo $product->getPCat()."\n";
                    echo '</span>'."\n";
                    echo '</h5>'."\n";
                ?>



            </div>
            <div class="shop-path col-md float-right text-right">
                <!-- path of products-->
                <a href="shop.php">Shop</a>
                <i class="fas fa-angle-double-right bg-transparent"></i>
                <?php
                    
                    echo "<a href='#' onclick=\"loadDoc('layouts/productLine.php?pLine=".$categories->getCatPLine()."', loadContent,'main')\">";
                  
                    echo $categories->getCatPLine();

                    echo "</a>";

                ?>

                <i class="fas fa-angle-double-right bg-transparent"></i>

                <?php
                    echo "<a href='#' onclick=\"loadDoc('layouts/categoriesList.php?cat=".$categories->getCatName()."',loadContent,'main')\">";
                    echo $categories->getCatName();
                    echo "</a>";
                ?>
                <i class="fas fa-angle-double-right bg-transparent"></i>

                <?php
                    echo "<a href='#' onclick=\"loadDoc('layouts/productDetails.php?product=".$product->getPCode()."',loadContent,'main')\">";
                    echo $product->getPName();
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
                            echo '<span id="productName">'.$product->getPName().'</span>'."\n";
                            ?>
                        </h5>
                    </div>
                </div>

                <!-- Product Image-->

                <div class="row pt-3 pl-3">
                    <div class="col-md-12 pl-4 ">
                        <?php
                        //print image url
                        echo '<img class="w-100" src="'.$product->getPImageURL().'" />';
                        ?>

                    </div>
                </div>

                <div class="row pt-3 pl-3">
                    <div class="col-md-12 pl-4 ">
                        <h5><span class="border-bottom border-secondary">Product Description</span></h5>
                        <p class="my-3">
                            <?php
                            // product description

                            echo $product->getPDescript();
                            ?>
                        </p>

                    </div>
                </div>



            </div>

            <!-- price info-->
            <div class="col-md-4 ">
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">
                        Vendor : &nbsp;&nbsp;&nbsp;
                        <?php
                       


                        echo "<a href='#' onclick=\"loadDoc('layouts/vendorDetails.php?vendor=".$vendor->getVenCode()."',loadContent,'main')\">\n";
                        echo $product->getPVendor();
                        echo "</a>";
                        ?>
                    </div>
                </div>
                <div class="row pl-4">
                    <div class="col-md-12 border-bottom bg-white  py-3">


                        <?php
                                if ($product->getSalePrice()===$product->getPSuggestedPrice()) {
                                } else {
                                    echo '<span class="text-secondary mr-5">'."\n";
                                    echo '<span class="text-dark"><strong>Original Price: </strong></span>';
                                    echo '<del>';
                                    echo $product->getPSuggestedPrice();
                                    echo '</del>'."\n";
                                    echo '</span>'."\n";

                                    echo '<span class="text-success">'."\n".
                                    "<strong>"."\n ";
                                    echo '<span class="text-dark">Now :'."\n ";
                                    echo '</span>'."\n ";

                                    echo '<span id="salePrice">';
                                    echo $product->getSalePrice();
                                    echo '</span>';

                                    echo '</strong>'."\n".
                                    "</span>\n";
                                }
                            ?>


                    </div>
                </div>



                <div class="row pl-4">
                    <div class="col-md-12 bg-white  py-3 pl-3 pr-4">
                        <!-- Show product attributes-->
                        <div class="row">
                            <div class="col-md-6 py-3">
                                <?php
                                    $product->printAttOption();
                                ?>

                            </div>

                            <div class="col-md-6 py-3">
                                <div class="row">
                                    <div class="col-md-6 pt-1 pr-0 mr-0">
                                        <span class="align-bottom ">Quantity :</span>
                                    </div>
                                    <div class="col-md-6 pl-0 ml-0">
                                        <select id='selectQuantityBox' class='custom-select'>

                                            <?php
                                        for ($i=0;$i<10;$i++) {
                                            $quantity = $i+1;
                                            echo '<option value='.$quantity.'>';
                                            echo $i+1;
                                            echo '</option>';
                                        }
                                    ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pb-3">
                                <span id="stock_number" class="text-danger">
                                    <!-- will change the number of specified stock by ajax call-->
                                    <?php
                                    $product->printPTotalStock();
                                ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- generate a ajax query to checkout.php-->
                                <button id="btn_addCart" class="btn btn-lg btn-primary w-100" onclick="addCart()">Add to
                                    Cart</button>
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
                                href="https://www.facebook.com/sharer/sharer.php?u=#">';
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
                                    href="https://twitter.com/intent/tweet?url=#"
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
                            $sql = "SELECT * FROM `products` WHERE `vendorName`='".$product->getPVendor()."'";
                                            
                            $shopProductArr=getSQLResult($sql);
                            
                            if (count($shopProductArr)>=2) {
                                echo 'More '.$product->getPVendor().' products';
                            }
                                
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="row px-4 pt-3 ">
                    <?php
                    //print product with same vendor
                       
                    if (count($shopProductArr)>=2) {
                        printShopProductsRow($shopProductArr, 4, $product->getPCode());
                    }
                    ?>
                </div>

                <div class="row px-4">
                    <div class="col-md-12">
                        <?php
                        //print product with same category
                            $sql = "SELECT * FROM `products` WHERE `categoriesName`='".$product->getPCat()."'";
                    
                            $shopProductArr=getSQLResult($sql);
                            if (count($shopProductArr)>=2) {
                                echo '<h5>You may also interest in...</h5>';
                            }
                            
                        ?>

                    </div>
                </div>

                <div class="row px-4 pt-3 ">
                    <?php
                    if (count($shopProductArr)>=2) {
                        printShopProductsRow($shopProductArr, 4, $product->getPCode());
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>