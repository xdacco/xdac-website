/**
 * Add a negative letter spacing to Profile Builder email customizer menus.
 */

jQuery( document ).ready(function(){
    jQuery('li a[href$="admin-email-customizer"]').css("letter-spacing", "-0.7px");
    jQuery('li a[href$="user-email-customizer"]').css("letter-spacing", "-0.7px");
});

/*
 * Set the width of the shortcode input based on an element that
 * has the width of its contents
 */
function setShortcodeInputWidth( $inputField ) {
    var tempSpan = document.createElement('span');
    tempSpan.className = "wppb-shortcode-temp";
    tempSpan.innerHTML = $inputField.val();
    document.body.appendChild(tempSpan);
    var tempWidth = tempSpan.scrollWidth;
    document.body.removeChild(tempSpan);

    $inputField.outerWidth( tempWidth );
}

jQuery( document ).ready( function() {

    jQuery('.wppb-shortcode.input').each( function() {
        setShortcodeInputWidth( jQuery(this) );
    });

    jQuery('.wppb-shortcode.textarea').each( function() {
        jQuery(this).outerHeight( jQuery(this)[0].scrollHeight + parseInt( jQuery(this).css('border-top-width') ) * 2 );
    });

    jQuery('.wppb-shortcode').click( function() {
        this.select();
    });
});


/* make sure that we don;t leave the page without having a title in the Post Title field, otherwise we loose data */
jQuery( function(){
    if( jQuery( 'body').hasClass('post-new-php') ){

        if( jQuery( 'body').hasClass('post-type-wppb-rf-cpt') || jQuery( 'body').hasClass('post-type-wppb-epf-cpt') || jQuery( 'body').hasClass('post-type-wppb-ul-cpt') ){

            if( jQuery('#title').val() == '' ){
                jQuery(window).bind('beforeunload',function() {
                    return "This page is asking you to confirm that you want to leave - data you have entered may not be saved";
                });
            }

            /* remove beforeunload event when entering a title or pressing the puclish button */
            jQuery( '#title').keypress(function() {
                jQuery(window).unbind('beforeunload');
            });
            jQuery( '#publish').click( function() {
                jQuery(window).unbind('beforeunload');
            });
        }
    }
});


/* show hide fields based on selected options */
jQuery( function(){
    jQuery( '#wppb-rf-settings-args').on('change', '#redirect', function(){
        if( jQuery(this).val() == 'Yes' ){
            jQuery( '.row-url, .row-display-messages', jQuery(this).parent().parent().parent()).show();
        }
        else{
            jQuery( '.row-url, .row-display-messages', jQuery(this).parent().parent().parent()).hide();
        }
    });

    jQuery( '#wppb-epf-settings-args').on('change', '#redirect', function(){
        if( jQuery(this).val() == 'Yes' ){
            jQuery( '.row-url, .row-display-messages', jQuery(this).parent().parent().parent()).show();
        }
        else{
            jQuery( '.row-url, .row-display-messages', jQuery(this).parent().parent().parent()).hide();
        }
    });


    jQuery( '#wppb-ul-settings-args').on('click', '#visible-only-to-logged-in-users_yes', function(){
        jQuery( '.row-visible-to-following-roles', jQuery(this).parent().parent().parent().parent().parent().parent()).toggle();
    });

    jQuery( '#wppb-ul-faceted-args').on('change', '#facet-type', function(){
        if( jQuery(this).val() == 'checkboxes' ){
            jQuery( '.row-facet-behaviour, .row-facet-limit', jQuery(this).parent().parent().parent()).show();
        }else if( jQuery(this).val() == 'select_multiple' ){
            jQuery( '.row-facet-behaviour, .row-facet-limit', jQuery(this).parent().parent().parent()).show();
        }
        else{
            jQuery( '.row-facet-behaviour, .row-facet-limit', jQuery(this).parent().parent().parent()).hide();
            jQuery( '.row-facet-behaviour #facet-behaviour', jQuery(this).parent().parent().parent()).val('narrow');
        }
        if( jQuery(this).val() == 'search' ){
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="billing_country"] ').hide();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="shipping_country"] ').hide();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="billing_state"] ').hide();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="shipping_state"] ').hide();
        }
        else {
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="billing_country"] ').show();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="shipping_country"] ').show();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="billing_state"] ').show();
            jQuery( '#wppb-ul-faceted-args .row-facet-meta #facet-meta option[value="shipping_state"] ').show()
        }

    });

});

/*
 * Dialog boxes throughout Profile Builder
 */
jQuery( function() {
    if ( jQuery.fn.dialog ) {
        jQuery('.wppb-modal-box').dialog({
            autoOpen: false,
            modal: true,
            draggable: false,
            minWidth: 450,
            minHeight: 450
        });

        jQuery('.wppb-open-modal-box').click(function (e) {
            e.preventDefault();
            jQuery('#' + jQuery(this).attr('href')).dialog('open');
        });
    }
});
