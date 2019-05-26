<?php
    include_once('../library/php/eShopClass.php');

    use arabcci_chamber_eshop\product;
    use function arabcci_chamber_eshop\queryShopDB_PDO;

    if (isset($_REQUEST['list'])) {
        $listArr=json_decode($_REQUEST['list']);
        //list array carry multiple objects
        if (count($listArr)>0) {
            //debug
            //var_dump($listArr);
        }
    } else {
        exit();
    }
?>
<div class="container-fluid fade-in3">
    <div class="row justify-content-center">
        <div class="col-12 px-5">
            <div class=" row py-5 justify-content-center">

                <h2 class="text center">Checkout form</h2>

            </div>

            <div class="row">
                <div class="col-md-12 order-md-1 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">My Shopping Cart</span>
                        <span class="badge badge-secondary badge-pill"><?php print_r(count($listArr)); ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <?php
                            if (count($listArr)>0) {
                                $totalPrice=0;
                                foreach ($listArr as $item) {
                                    $sql= "SELECT productCode FROM products WHERE productName = ?";
                                    $resultArr= queryShopDB_PDO($sql, $item->{'_name'});
                                    $product = new product($resultArr[0]['productCode']);
                                    $totalPrice += floatval($item->{'_subTotal'});
                                    //print_r($listArr[0]->{'_name'});
                                    echo '<li class="list-group-item d-flex justify-content-between lh-condensed">'."\n";
                                    echo '<div>'."\n";
                                    echo '<h6 class="my-0">'.$item->{'_name'}.'</h6>'."\n";
                                    echo '<small class="text-muted">'.$product->getPDescript().'</small><br/>'."\n";
                                    echo '<small class="text-muted">'.$item->{'_attribute'}.'</small><br/>'."\n";
                                    echo '<small class="text-muted">Quantity: '.$item->{'_quantity'}.'</small><br/>'."\n";
                                    echo '</div>'."\n";
                                    echo '<div class="text-right">'."\n";
                                    echo '<h6 class="my-0">Sub Total</h6>'."\n";
                                    echo '<span class="text-muted">'.$item->{'_subTotal'}.'</span>'."\n";
                                    echo '</div>'."\n";
                                    echo '</li>'."\n";
                                }
                                //print total price
                                echo '<li class="list-group-item d-flex justify-content-between mt-2">'."\n";
                                echo '<span>Total (USD)</span>'."\n";
                                echo '<strong>'.$totalPrice.'</strong>'."\n";
                                echo '</li> '."\n";
                            } else {
                                echo'<li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0">No Item in Shopping Cart</h6>
                                            <small class="text-muted"></small>
                                        </div>
                                        <span class="text-muted"></span>
                                    </li>';
                            }
                            

                        /*
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Product name</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Second product</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$8</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">Third item</h6>
                                <small class="text-muted">Brief description</small>
                            </div>
                            <span class="text-muted">$5</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between mt-2">
                            <span>Total (USD)</span>
                            <strong>$20</strong>
                        </li>
                        */
                        ?>
                    </ul>
                </div>


                <div class="col-md-8 order-md-2">
                    <h4 class="mb-3">Billing address</h4>
                    <form class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                                <div class="invalid-feedback">
                                    Valid first name is required.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
                                <div class="invalid-feedback">
                                    Valid last name is required.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="text" class="form-control" id="username" placeholder="Username" required>
                                <div class="invalid-feedback" style="width: 100%;">
                                    Your username is required.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email <span class="text-muted">(Optional)</span></label>
                            <input type="email" class="form-control" id="email" placeholder="you@example.com">
                            <div class="invalid-feedback">
                                Please enter a valid email address for shipping updates.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
                            <div class="invalid-feedback">
                                Please enter your shipping address.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                <select class="custom-select d-block w-100" id="country" required>
                                    <option value="">Choose...</option>
                                    <option>United States</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a valid country.
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                <select class="custom-select d-block w-100" id="state" required>
                                    <option value="">Choose...</option>
                                    <option>California</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a valid state.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip" placeholder="" required>
                                <div class="invalid-feedback">
                                    Zip code required.
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="same-address">
                            <label class="custom-control-label" for="same-address">Shipping address is the same as my
                                billing
                                address</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="save-info">
                            <label class="custom-control-label" for="save-info">Save this information for next
                                time</label>
                        </div>
                        <hr class="mb-4">

                        <h4 class="mb-3">Payment</h4>

                        <div class="d-block my-3">
                            <div class="custom-control custom-radio">
                                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input"
                                    checked required>
                                <label class="custom-control-label" for="credit">Credit card</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="debit" name="paymentMethod" type="radio" class="custom-control-input"
                                    required>
                                <label class="custom-control-label" for="debit">Debit card</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input"
                                    required>
                                <label class="custom-control-label" for="paypal">PayPal</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cc-name">Name on card</label>
                                <input type="text" class="form-control" id="cc-name" placeholder="" required>
                                <small class="text-muted">Full name as displayed on card</small>
                                <div class="invalid-feedback">
                                    Name on card is required
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cc-number">Credit card number</label>
                                <input type="text" class="form-control" id="cc-number" placeholder="" required>
                                <div class="invalid-feedback">
                                    Credit card number is required
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="cc-expiration">Expiration</label>
                                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                                <div class="invalid-feedback">
                                    Expiration date required
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="cc-cvv">CVV</label>
                                <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                                <div class="invalid-feedback">
                                    Security code required
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <p class="mb-1">&copy; 2017-2019 Company Name</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="#">Privacy</a></li>
                    <li class="list-inline-item"><a href="#">Terms</a></li>
                    <li class="list-inline-item"><a href="#">Support</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>