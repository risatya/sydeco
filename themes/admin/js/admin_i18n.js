/**
 * Configurations
 */
var config = {
    logging : true,
    baseURL : location.protocol + "//" + location.hostname + "/admin/"
};


/**
 * Let's get started
 */
$(document).ready(function() {

    /**
     * Enable tooltips
     */
    $('.tooltips').tooltip();


    /**
     * Detect items per page change on all list pages and send users back to page 1 of the list
     */
    $('select#limit').change(function() {
        var limit = $(this).val();
        var currentUrl = document.URL.split('?');
        var uriParams = "";
        var separator;

        if (currentUrl[1] != undefined) {
            var parts = currentUrl[1].split('&');

            for (var i = 0; i < parts.length; i++) {
                if (i == 0) {
                    separator = "?";
                } else {
                    separator = "&";
                }

                var param = parts[i].split('=');

                if (param[0] == 'limit') {
                    uriParams += separator + param[0] + "=" + limit;
                } else if (param[0] == 'offset') {
                    uriParams += separator + param[0] + "=0";
                } else {
                    uriParams += separator + param[0] + "=" + param[1];
                }
            }
        } else {
            uriParams = "?limit=" + limit;
        }

        // reload page
        window.location.href = currentUrl[0] + uriParams;
    });


    /**
     * Enable TinyMCE WYSIWYG editor on any textareas with the 'editor' class
     */
    tinymce.init({
        selector: "textarea.editor",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons paste textcolor"
        ],
        toolbar1: "styleselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | hr link image media emoticons | cut copy paste pastetext | print preview | code",
        image_advtab: true
    });


    /**
     * Apply form-control class and id to timezones dropdown on settings form
     */
    $('select[name=timezones]').addClass('form-control').attr('id', "timezones");


    // Jsi18n Demonstration used on the dashboard
    $('#jsi18n-sample').click(function(e) {
        if (e.preventDefault) {
            e.preventDefault();
        } else {
            e.returnValue = false;
        }
        alert("{{admin dashboard jsi18n-sample}}");
    });

}); // end $(document).ready()
