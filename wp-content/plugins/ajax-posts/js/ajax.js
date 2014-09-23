/* 
 *  Plugin Name: Ajax posts gallery
 *  Plugin URI: http://
 *  Description: Adds new post type to existing ones, allows to view posts as gallery with ajax loading
 *  Version: 1.0
 *  Author: Anton Presnyakov
 *  Author URI: http://
 * License: MIT
 */

jQuery(document).ready(function($) {
    var data = {
        'action': 'ajp_refresh',
        'url': ajp_object.url,
        'cat': 0,
        
    };
    
    function send_response ( _this ) {
        var _data = jQuery.extend({}, data);
        
        _data.url = jQuery(_this).prop('href');        
        var matches;
        if ( _data.url && (matches = _data.url.match(/[&?]cat=(\d+)/)) ) {
            _data.cat = matches [1];
        }
        
        if ( _data.url && (matches = _data.url.match(/[&?]ajppost=(.*)(&|$)/)) ) {
            _data.ajppost = matches [1];
        }
                
        jQuery.post(ajp_object.ajax_url, _data, function(response) {
           //console.log(response);
            jQuery('#content').html(response);
        });        
        
        return _data;
    }
    
    function refresh_social_buttons ( data ) {
        var fb = null;
        if ( fb = jQuery('.social-buttons .fb-social-button') ){
            try {
                fb = fb [0];
            } catch ( ex ) {
                
            }
            
            if ( jQuery ( fb ).attr ( 'href' ) ){
                jQuery ( fb ).attr ( 'href', data.url );
            }
        }
            
        try {
            FB.XFBML.parse(); 
        } catch ( ex ) {
            
        }
    }
    
    jQuery ('.ajp-categories a').on( 'click', function () {        
        var data = send_response ( this );
        refresh_social_buttons ( data );
        return false;
    });
    
    jQuery ('body').on('click', '.ajp-post-navigation a', function () {
        var data = send_response ( this );
        refresh_social_buttons ( data );
        return false;
    });
    
});