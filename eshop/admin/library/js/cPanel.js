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

