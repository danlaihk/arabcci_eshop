<?php

include_once('../library/php/cPanelControl.php');

/*
print_r($_REQUEST); //debug
print_r("\n".$_SERVER['HTTP_REFERER']);
*/


loadProductPanel();



?>
<script src="../library/js/cPanel.js"></script>

<script>
$(document).ready(function() {
    $("btn[type=submit]").click(function() {

        //var formStr = $("#pinsertForm").serialize();
        //alert(formStr);
        $("#pinsertForm").submit();
    });

    $("#pinsertForm").submit(function(e) {
        e.preventDefault();
        $('input[name=action]').val('add');
        var formData = new FormData(this);
        // var formData = $("#pinsertForm").serialize();
        // console.log(formData);

        var valid = true;
        $('input[type=text]').each(function() {
            if (!$(this).val()) {

                return valid = false;
            }
        });

        $('#pinsertForm select').each(function() {
            if ($("#pinsertForm select").val() == 0) {

                return valid = false;
            }
        });

        $('#pinsertForm textarea').each(function() {
            if (!$("#pinsertForm textarea").val()) {

                return valid = false;
            }
        });

        $('input[name=action]').val('form');

        if (valid == true) {
            //run ajax

            $.ajax({
                url: 'productCMS.php',
                type: 'post',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {


                    //alert(returnData);
                    if (data == "duplicate") {
                        alert('You entered a duplicate product name or product code.');
                    } else {
                        alert('input Success!!');

                    }

                }
            });

        } else {
            alert('Please enter complete information!');
        }

        // console.log(formStr);
    });
});
</script>
<div class="row pt-5">
    <div class="col-12">
        <form id="pinsertForm" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="hidden" name='action' value='form'>

                    <label for="inputPCode">Product Code</label>
                    <input type="text" class="form-control" name="inputPCode" placeholder="Product Code">
                </div>

                <div class="form-group col-md-6">
                    <label for="inputPName">Product Name</label>
                    <input type="text" class="form-control" name="inputPName" placeholder="Product Name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="selectCategories">Categories</label>
                    <select class="custom-select my-1 mr-sm-2" name="selectCategories">
                        <option value="0" selected>Select Product Categories</option>
                        <?php echo printCategoriesOpts();?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="selectVendor">Vendor</label>
                    <select class="custom-select my-1 mr-sm-2" name="selectVendor">
                        <option value="0" selected>Select Product Vendors</option>
                        <?php echo printVendorsOpts();?>
                    </select>
                </div>


            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputDescript">Description</label>
                    <textarea class="form-control" name="inputDescript" rows="3"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputAttrs">Product Attributes(eg:
                        attribute1-stock|attribute2-stock)</label>
                    <textarea class="form-control" name="inputAttrs" rows="3"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="inputBuyprice">Buy Price</label>
                    <input type="text" class="form-control" name="inputBuyprice" placeholder="Product Buy Price">
                </div>

                <div class="form-group col-md-3">
                    <label for="inputMSRP">MSRP</label>
                    <input type="text" class="form-control" name="inputMSRP"
                        placeholder="Manufacturer's Suggested Retail Price">
                </div>

                <div class="form-group col-md-3">
                    <label for="inputDiscount">Discount(%)</label>
                    <input type="text" class="form-control" name="inputDiscount" placeholder="Discount(%)">
                </div>
                <div class="form-group col-md-3">
                    <label for="pImageUpload">Product Image(730px*566px)</label>
                    <input type="file" accept="image/*" class="form-control-file" name="pImageUpload">
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