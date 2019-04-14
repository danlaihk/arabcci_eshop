     <!-- footer nav link-->

     <?php
    include('library/php/loadFooter.php');
    

    ?>

     <div class="row mt-5">
         <div class="col-md-2 d-flex justify-content-center">
             <a class="title" href="#">
                 <h3 class="  font-weight-bold "><span class="align-middle">Shop</span></h3>
             </a>
         </div>
         <div class="col-md-10 pt-1">
             <ul class="nav text-white px-0 mx-0  d-flex justify-content-start">
                 <?php

                $pLineInfoArr= getProductLineInfo();
                printFooterNavLink($pLineInfoArr);
                ?>
             </ul>
         </div>
     </div>
     <!-- footer information-->
     <div class="row bg-white justify-content-center px-5 pt-3 pb-3">
         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/card_visa.gif">
         </div>
         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/card_mc.gif">
         </div>
         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/wechatPay.jpg">
         </div>
         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/alipay.jpg">
         </div>
         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/payment_options_bitcoin.gif">
         </div>

         <div class="col-md-1 d-flex justify-content-center align-items-center ">

             <img src="library/image/paymentMethods/unionPay.jpg">
         </div>

     </div>

     </div>
     <div class="row bg-white">
         <div class="col-sm-12 text-center">
             <p>Copyright © . All rights reserved.</p>
             <p>tele： email： address：</p>

         </div>
     </div>