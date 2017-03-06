'use strict';

$(function() {

    const TYPE_BLOG_POST         = "1";
    const TYPE_EMAIL             = "5";
    const TYPE_FACEBOOK_POST     = "2";
    const TYPE_HUBSPOT_BLOG_POST = "4";
    const TYPE_TWEET             = "3";
    const TYPE_WEBSITE_PAGE      = "6";

    $('#contentType').change(refreshFields);

    function refreshFields() {
        const type = $('#contentType').val();
        const showClass = (function() {
            switch (type) {
                case TYPE_EMAIL: return '.flexible-fields-email';
                default:
            }
        })();

        const hideClass = (function() {
            switch(type) {
                case TYPE_EMAIL: return '.flexible-fields-hide-email';
                default:
            }
        })();

        $('.flexible-fields').slideUp('fast');
        $('.flexible-fields-hide').slideDown('fast');

        $(hideClass).slideUp('fast');
        $(showClass).slideDown('fast');
    }

    refreshFields();

});