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
        data: {

            userName: userName

        },
        success: function (data) {
            $("#cmsContent").html(data);
        }

    });
}