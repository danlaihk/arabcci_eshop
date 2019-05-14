<?php

include_once('../library/php/cPanelControl.php');

/*
print_r($_REQUEST); //debug
print_r("\n".$_SERVER['HTTP_REFERER']);
*/

loadProductPanel();

?>
<script src="library/js/adminCMS.js"></script>

<div class="row pt-5">
    <div class="col-12">
        <form>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputPCode">Product Code</label>
                    <input type="text" class="form-control" id="inputPCode" placeholder="Product Code">
                </div>

                <div class="form-group col-md-6">
                    <label for="inputPName">Product Name</label>
                    <input type="text" class="form-control" id="inputPName" placeholder="Product Name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="selectCategories">Categories</label>
                    <select class="custom-select my-1 mr-sm-2" id="selectCategories">
                        <option value="0" selected>Select Product Categories</option>
                        <?php echo printCategoriesOpts();?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="selectVendor">Vendor</label>
                    <select class="custom-select my-1 mr-sm-2" id="selectVendor">
                        <option value="0" selected>Select Product Vendors</option>
                        <?php echo printVendorsOpts();?>
                    </select>
                </div>


            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputDescript">Description</label>
                    <textarea class="form-control" id="inputDescript" rows="3"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputAttrs">Product Attributes(eg:
                        attribute1-stock|attribute2-stock)</label>
                    <textarea class="form-control" id="inputAttrs" rows="3"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="inputBuyprice">Buy Price</label>
                    <input type="text" class="form-control" id="inputBuyprice" placeholder="Product Buy Price">
                </div>

                <div class="form-group col-md-3">
                    <label for="inputMSRP">MSRP</label>
                    <input type="text" class="form-control" id="inputMSRP"
                        placeholder="Manufacturer's Suggested Retail Price">
                </div>

                <div class="form-group col-md-3">
                    <label for="inputDiscount">Discount(%)</label>
                    <input type="text" class="form-control" id="inputDiscount" placeholder="Discount(%)">
                </div>
                <div class="form-group col-md-3">
                    <label for="pImageUpload">Product Image(730px*566px)</label>
                    <input type="file" class="form-control-file" id="pImageUpload">
                </div>
            </div>




            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</div>

<div class="row pt-5">
    <div class="col-12 overflow-auto">
        <table class="table table-striped w-100">
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