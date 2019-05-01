<?php
include_once('../library/php/eShopClass.php');
use function arabcci_chamber_eshop\queryShopDB_PDO;

////// used the relative path from categoriesList.php to loadProductsInfo.php
    include_once('../library/php/loadProductsInfo.php');

    ////get ajax queries value
    ///product line
    $pLine = $_REQUEST["pLine"];
     /////echo $cat;
     
    
?>

<div class="row justify-content-center">
    <div class="col-sm-12 pt-3 pb-3 px-5">

        <p>
            <span class="float-left font-weight-bold">
                <?php

            
               
                    /////debug line
                    ////print_r($productLine);
                    echo $pLine;

                ?>
            </span>
            <!-- print the path and links-->
            <span class="float-right">

                <a href="shop.php">Shop</a>
                <i class="fas fa-angle-double-right bg-transparent"></i>
                <?php
                    
                    echo "<a href='#' onclick=\"loadDoc('layouts/productLine.php?pLine=".$pLine."', loadContent,'main')\">";
                  
                    echo $pLine;

                    echo "</a>";

                ?>


            </span>
        </p>

    </div>
</div>


<div class="row fade-in3">

    <!-- Categories-->
    <div class="col-3  d-none d-md-block">

        <div class="row justify-content-start">
            <div class="card col-12 border-0 ">
                <div class="card-body pt-0 pl-4 ">
                    <ul class="list-group pl-0">


                        <?php
                            ////print categories of specific product line
                            $sql = "SELECT categoriesName FROM categories WHERE productLine= ?";
                            $catArr = queryShopDB_PDO($sql, $pLine);

                  
                            printCatSideMenu($catArr);
                        ?>



                    </ul>
                </div>
            </div>
        </div>


    </div>

    <!-- product Index-->
    <div id="productIndex" class="col-md-9">
        <div class="row ">
            <div class="col">
                <p><i class="fab fa-hotjar fourthRowIcon mr-2"></i>
                    <span>
                        <?php
                        echo $pLine.'  in stock';
                    ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Sixth row Best Activities Contents-->
        <div class="row px-3">

            <?php
            

            //change 2d query result into 1d array;
            $catInArr=array();
            for ($i=0;$i<count($catArr);$i++) {
                array_push($catInArr, $catArr[$i]['categoriesName']);
            }
            
            $cat  = str_repeat('?,', count($catArr) - 1) . '?';
            $sql = "SELECT * FROM products WHERE categoriesName IN ($cat)";
            
            $pInfoArr=queryShopDB_PDO($sql, $catInArr);
            if (count($pInfoArr)>0) {
                printProductRow($pInfoArr, 3);
            } else {
                echo 'Sorry, no such goods in stock.';
            }
            //printProductRow($pInfoArr, 3);

            ?>



        </div>

    </div>

</div>