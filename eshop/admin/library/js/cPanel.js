function loadSubCMS(subPage, userName) {
    var targetPage = '';
    switch (subPage) {
        case "product":
            // code block
            targetPage = 'productCMS.php';
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
                pCode: pCode
            },


            success: function (data) {
                alert('Deleted!');
                location.reload();
            }
        });
    }
}