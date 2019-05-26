function loadSubCMS(subPage) {
    var targetPage = '';
    switch (subPage) {
        case "product":
            // code block
            targetPage = 'productCMS.php';
            break;

        case "feature":
            // code block
            targetPage = 'featureCMS.php';
            break;


        case "vendor":
            // code block
            targetPage = 'vendorCMS.php';
            break;
        //default:
        // code block
    }

    $.ajax({
        type: 'GET',

        url: targetPage,

        success: function (data) {

            $("#cmsContent").html(data);


        }

    });
}

function logoutPanel() {
    $.ajax({
        type: 'GET',

        url: 'cPanel.php',
        data: {

            action: 'logout'

        },
        success: function (data) {
            if (data == 'ok') {
                location.href = '../CMSlogin.php';
            }
        }

    });
}

function deleteProduct(pCode) {
    var valid = confirm("Are you sure to delete this product?");

    if (valid == true) {
        $.ajax({
            url: 'productCMS.php',
            type: 'post',
            data: {
                action: 'delProduct',
                inputPCode: pCode
            },


            success: function (data) {
                if (data == 'usedByFeature') {
                    alert('The Product is in featured!');
                    location.reload();
                } else if (data = 'deleted') {
                    alert('Deleted!');
                    location.reload();
                } else {
                    alert('Error!');
                }
            }
        });
    }
}

function deleteFeature(fCode) {
    var valid = confirm("Are you sure to delete this product?");

    if (valid == true) {
        $.ajax({
            url: 'featureCMS.php',
            type: 'post',
            data: {
                action: 'delFeature',
                inputCode: fCode
            },


            success: function (data) {
                if (data = 'deleted') {
                    alert('Deleted!');
                    location.reload();
                } else {
                    alert('Error!');
                }
            }
        });
    }
}

function deleteVendor(vName) {
    var valid = confirm("Are you sure to delete this product?");

    if (valid == true) {
        $.ajax({
            url: 'vendorCMS.php',
            type: 'post',
            data: {
                action: 'delVendor',
                inputName: vName
            },


            success: function (data) {
                if (data == 'usedByProducts') {
                    alert('This vendor has related products in Database!');
                    location.reload();
                } else if (data = 'deleted') {
                    alert('Deleted!');
                    location.reload();
                } else {
                    alert('Error!');
                }
            }
        });
    }
}