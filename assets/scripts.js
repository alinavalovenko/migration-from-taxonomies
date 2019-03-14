jQuery(document).ready(function ($) {
    var btnRun = $('.fd-run-migrate');

    btnRun.click(function (event) {
        data = {
            action: 'fd_single_migrate',
            term_id: $(this).attr('data-term')
        };
        var bnt =  $(this);
        $.post(ajaxurl, data, function (response) {
            alert(response);
            bnt.attr("disabled", true);
        });
    });
});