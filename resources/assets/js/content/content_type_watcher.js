'use strict';

$(function() {

    const TYPE_BLOG_POST         = "1";
    const TYPE_FACEBOOK_POST     = "2";
    const TYPE_TWEET             = "3";
    const TYPE_HUBSPOT_BLOG_POST = "4";
    const TYPE_EMAIL             = "5";
    const TYPE_WEBSITE_PAGE      = "6";
    const TYPE_LINKEDIN          = "8";
    const TYPE_CUSTOM            = "9";

    $('#contentType').change(refreshFields);

    function refreshFields() {
        const type = $('#contentType').val();

        $('.flexible-fields-email').slideUp('fast');

        switch (type) {
            case TYPE_EMAIL:
                $('.flexible-fields-email').slideDown('fast');
                $('.flexible-fields-seo').slideUp('fast');
                break;
            case TYPE_FACEBOOK_POST:
            case TYPE_TWEET:
            case TYPE_LINKEDIN:
                $('.flexible-fields-seo').slideUp('fast');
                break;
            default:
                $('.flexible-fields-seo').slideDown('fast');
        }
    }

    refreshFields();

});