<?php

include_once('../library/php/cPanelControl.php');

/*
print_r($_REQUEST); //debug
print_r("\n".$_SERVER['HTTP_REFERER']);
*/


loadVendorPanel();



?>
<script src="../library/js/cPanel.js"></script>

<script>
$(document).ready(function() {
    $("btn[type=submit]").click(function() {

        //var formStr = $("#submitForm").serialize();
        //alert(formStr);
        $("#submitForm").submit();
    });
    $('#selectAction').change(function() {
        if ($('#selectAction').val() == 1) {
            $('input[name=action]').val('add');
        }
        if ($('#selectAction').val() == 2) {
            $('input[name=action]').val('update');
        }

    });
    $("#submitForm").submit(function(e) {
        e.preventDefault();


        var formAction = $('input[name=action]').val();

        if (formAction == 'form') {
            alert('Please select the type of action.');
        } else {
            var formData = new FormData(this);
            // var formData = $("#submitForm").serialize();
            // console.log(formData);

            var valid = true;
            $('input[type=text]').each(function() {
                if (!$(this).val()) {

                    return valid = false;
                }
            });

            $('#submitForm select').each(function() {
                if ($("#submitForm select").val() == 0) {

                    return valid = false;
                }
            });

            $('#submitForm textarea').each(function() {
                if (!$("#submitForm textarea").val()) {

                    return valid = false;
                }
            });

            $('input[name=action]').val('add');

            if (valid == true) {
                //run ajax

                $.ajax({
                    url: 'vendorCMS.php',
                    type: 'post',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {


                        //alert(returnData);
                        if (data == "duplicate") {
                            alert('You entered a duplicate vendor code .');
                        } else if (data == "noResult") {
                            alert(
                                'There is no such product with the vendor you inputted'
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
            <option value="1" selected>Add Vendor Info</option>
            <option value="2">Update Vendor Info</option>
        </select>
    </div>
</div>

<div class='row pt-3'>
    <div class="col-12">
        <p>Please enter the information you want to add/update, all information should not be blank.<br>
            The vendor code or name must be UNQIE.<br>
            The vendor code CANNOT be modified after added.<br>
            At update action, the target of update depends on its vendor code.
        </p>
        <form id="submitForm" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="hidden" name='action' value='add'>

                    <label for="inputCode">Vendor Code</label>
                    <input type="text" class="form-control" name="inputCode" placeholder="Feature Code">


                </div>
                <div class="form-group col-md-6">
                    <label for="inputName">Vendor Name</label>
                    <input type="text" class="form-control" name="inputName" placeholder="Vendor Name">
                </div>

            </div>
            <div class="form-row">

                <div class="form-group col-md-12">

                    <label for="inputlink">Link</label>
                    <input type="text" class="form-control" name="inputlink" placeholder="Vendor Link">
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputDescript">Description</label>
                    <textarea class="form-control" name="inputDescript" rows="3"></textarea>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="vImageUpload">Vendor Image</label>
                    <input type="file" accept="image/*" class="form-control-file" name="vImageUpload">
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
                    <th>Vendor Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Vendor Link</th>
                    <th>Image URL</th>
                    <th>Hit Rate</th>
                </tr>
            </thead>
            <?php echo printVendorListHTML();?>
        </table>
    </div>
</div>