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
    $('#selectAction').change(function() {
        if ($('#selectAction').val() == 1) {
            $('input[name=action]').val('add');
        }
        if ($('#selectAction').val() == 2) {
            $('input[name=action]').val('update');
        }

    });
    $("#pinsertForm").submit(function(e) {
        e.preventDefault();

        //$('input[name=action]').val('add');
        var formAction = $('input[name=action]').val();

        if (formAction == 'form') {
            alert('Please select the type of action.');
        } else {
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

            $('input[name=action]').val('add');

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
                        } else if (data == "noResult") {
                            alert(
                                'There is no such product with the productCode you inputted'
                            );
                        } else if (data == 'updateCompleted') {
                            console.log(data);
                            alert('Update Success!!');
                            location.reload();
                        } else if (data == 'ufileError') {
                            alert('Please selected uploaded image');
                        } else if (data == 'passed') {
                            console.log(data);
                            alert('Input Success!!');
                            location.reload();
                        } else {
                            console.log(data);
                            alert('unhandle condition');
                        }

                    }
                });

            } else {
                alert('Please enter complete information!');
            }

            // console.log(formStr);
        }


    });
});
</script>
<div class="row pt-3">
    <div class="col-12">
        <label>Please choose the action of cms</label>
        <select id="selectAction">
            <option value="1" selected>Add Product Info</option>
            <option value="2">Update Product Info</option>
        </select>
    </div>
</div>

<div class='row pt-3'>
    <div class="col-12">
        <p>Please enter the information you want to add/update, all information should not be blank.<br>
            The product code or name must be UNQIE.<br>
            The product code CANNOT be modified after added.<br>
            At update action, the target of update depends on its product code.
        </p>
        <form id="pinsertForm" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="hidden" name='action' value='add'>

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
                    <th></th>
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