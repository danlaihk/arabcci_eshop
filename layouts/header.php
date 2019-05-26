<!-- nav bar-->
<?php
    include('library/php/loadHeader.php');
    include_once('library/php/eShopClass.php');
    

    $pLineArr = getProductLineInfo();

    ?>

<nav class="search-bar">
    <div class="row  py-2">
        <div class="col-md-2 px-5 ">
            <a class="navbar-brand font-weight-bolder text-white" href="shop.php">Shop</a>
        </div>
        <!-- need to be change after language button is implemented-->
        <div class="  my-2 my-md-0  col-md-8">

            <input id="search_box" class="form-control   rounded-left search_form" type="text"
                placeholder="Search for more" aria-label="Search"
                oninput="showResult('library/php/search.php?q='+ this.value,'livesearch_result')"
                onkeyup="showResult('library/php/search.php?q='+ this.value,'livesearch_result')">

        </div>


        <div id="shopping_cart_container" class="col-md-1">

            <div class="row justify-content-end pt-1">


                <div id="cart_counter" class='counter rounded bg-danger text-white'></div>
                <i id="cart_icon" class="fas fa-lg fa-shopping-cart bg-transparent text-white pt-1"></i>

            </div>

        </div>
        <div class="col-md-1 ">
            <!-- need to be change after language button is implemented-->

            <div class="row justify-content-center ">
                <a id="loginBtn" class="languageBtn btn btm-sm  font_white pt-0 pb-1 px-1 mr-2" href="#">Login
                </a>
            </div>
        </div>
    </div>
    <div class="row ">
        <div id="livesearch_result" class="search-result  bg-white text-dark ">


        </div>
        <div id="cart_list"
            class="cart-list  bg-white text-dark rounded-bottom border-bottom border-left border-right ">

            <div class="row">
                <div class="col-12 pb-2 px-3 mx-0 ">
                    <table class="table table-striped pt-0 pl-0 cart-list-table ">

                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Attribute</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Sub-Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart_item_container">
                            <!--example row -->
                            <!--
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                        </tr>
                        -->
                        </tbody>
                    </table>
                </div>

            </div>


            <div class="row">
                <div class="col-12 px-4 py-3 mx-0">
                    <a id="btnCartList" href="#" class="btn btn-sm btn-primary"><span>Show Details...</span></a>
                </div>
            </div>
        </div>
    </div>

</nav>




<!-- dropdown menu-->
<div id="header_dropdown_menu_row" class="dropdown-row row mt-0 d-none d-md-inline">
    <div class="btn-group col-sm-12 bg-white justify-content-center px-0 mx-0" role="group">
        <?php
            for ($i = 0; $i < count($pLineArr); $i++) {
                echo '<div class="dropdown dropdownBtn col tenPercentWidth p-0 mx-0">
                    <button class="btn dropdown_btn btn-block  dropdown_btn " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="row justify-content-center pt-2">
                            <div class="col">
                                <span class="fa-stack fa-1x text-primary">
                                    <i class="fas fa-heartbeat fa-stack-1x"></i>';

                //////////////print icon
                echo '<i class="' . $pLineArr[$i]['icon'] . ' fa-stack-2x"></i>';
                //////////////print icon

                echo ' </span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col mb-0 pb-0">';

                //////////print productLine name
                echo '<span>' . $pLineArr[$i]['productLine'] . '</span>';
                //////////print productLine name

                echo '</div>
                        </div>
                    </button>
                <div class="dropdown-menu header_dropdown_menu " aria-labelledby="dropdownMenuButton">';

                //////print categories name of specific productLine
                $catArr = getCategoriesInfo($pLineArr[$i]['productLine']);
                
                
               
                for ($part = 0; $part < count($catArr); $part++) {
                    echo "<a class='dropdown-item' href='#'
                        onclick=\"loadDoc('layouts/categoriesList.php?cat=".$catArr[$part]['categoriesName']."', loadContent,'main')\">".$catArr[$part]['categoriesName']."</a>";
                }
                //////print categories name of specific productLine
                
                echo '
                </div>
            </div>';
            }
            ?>