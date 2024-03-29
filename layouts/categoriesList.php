<?php
    ////// used the relative path from categoriesList.php to loadProductsInfo.php
    include_once('../library/php/loadProductsInfo.php');

    include_once('../library/php/eShopClass.php');
    
    use function arabcci_chamber_eshop\queryShopDB_PDO;
    use arabcci_chamber_eshop\productLine;
    use arabcci_chamber_eshop\categories;

    ////get ajax queries value
    $cat = $_REQUEST["cat"];
     /////echo $cat;
    $categories= new categories($cat);
?>

<div class="row justify-content-center">
    <div class="col-sm-12 pt-3 pb-3 px-5">

        <p>
            <span class="float-left font-weight-bold">
                <?php

                    /// get product line of the query
                    $sql = "SELECT productLine FROM `categories` WHERE `categoriesName`= ?";
                    $productLineArr = queryShopDB_PDO($sql, $cat);
               
               
                    /////debug line
                    ////print_r($productLine);
                    $productLine=new productLine($productLineArr[0]['productLine']);
                    echo $productLine->getPLineName();

                ?>
            </span>
            <!-- print the path and links-->
            <span class="float-right">

                <a href="shop.php">Shop</a>
                <i class="fas fa-angle-double-right bg-transparent"></i>
                <?php
                    
                    echo "<a href='#' onclick=\"loadDoc('layouts/productLine.php?pLine=".$productLine->getPLineName()."', loadContent,'main')\">";
                  
                    echo $productLine->getPLineName();

                    echo "</a>";

                ?>

                <i class="fas fa-angle-double-right bg-transparent"></i>

                <?php
                    echo "<a href='#' onclick=\"loadDoc('layouts/categoriesList.php?cat=".$cat."',loadContent,'main')\">";
                    echo $cat;
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
                            $sql = "SELECT categoriesName FROM `categories` WHERE `productLine`= ?";
                            $catArr = queryShopDB_PDO($sql, $productLine->getPLineName());

                  
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
                        echo $cat.'  in stock';
                    ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Sixth row Best Activities Contents-->
        <div class="row px-3">

            <?php

           
           
            $sql = "SELECT * FROM products WHERE categoriesName = ? ORDER BY `hitrate` DESC ";

            ////debug line
            /////print_r(getSQLResult($sql));

            $pInfoArr = queryShopDB_PDO($sql, $cat);
            printProductRow($pInfoArr, 3);

            ?>



        </div>

    </div>

</div>