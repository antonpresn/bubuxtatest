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
        //'page_id':ajp_object.page_id
    };
    
    function send_request ( _this ) {
        var _data = jQuery.extend ( {}, data );
        
        _data.url = jQuery ( _this ).prop ( 'href' );     
        
        var matches;
        
        if ( _data.url && (matches = _data.url.match (/[&?]cat=(\d+)/)) ) {
            _data.cat = matches [1];
            data.cat = _data.cat;
        }
        
        if ( _data.url && (matches = _data.url.match (/[&?]ajppost=(.*?)(&|$)/)) ) {
            _data.ajppost = matches [1];
        }
                
        jQuery.post ( ajp_object.ajax_url, _data, function ( response ) {

            jQuery ( '#content' ).html ( response.content );
            
            if ( response.hasOwnProperty ('url') ) {
                _data.url = response.url;                
            }
            
            refresh_social_buttons ( _data );
            
            refresh_links ( response );
            
        });        
        
        return _data;
    }
    
    function refresh_social_buttons ( data ) {
        if ( !data.url ) {
            return;
        }
        
        var fb = null;
        
        if ( fb = jQuery('.social-buttons .fb-social-button') ) {
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
        
        var vk = null;
        
        if ( vk = jQuery ('#vk_like') ) {            
            vk.html ( '' );            
        }
        
        VK.Widgets.Like ( "vk_like", {pageUrl: data.url} );
                
        var od = null;
        
        if ( od = jQuery ('#ok_shareWidget') ) {
            od.html ( '' );
        }
        
        var d = document;
        var id = "ok_shareWidget";
        var did = data.url;
        var st = "{width:170,height:30,st:'rounded',sz:20,ck:3}";
        !function (d, id, did, st) {
            var js = d.createElement("script");
            js.src = "http://connect.ok.ru/connect.js";
            js.onload = js.onreadystatechange = function () {
            if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                  if (!this.executed) {
                    this.executed = true;
                    setTimeout(function () {
                          OK.CONNECT.insertShareWidget(id,did,st);
                    }, 0);
                  }
            }};
            d.documentElement.appendChild(js);
          }(document,"ok_shareWidget",data.url,"{width:170,height:30,st:'rounded',sz:20,ck:3}");
//        var js = d.createElement("script");
//        js.src = "http://connect.ok.ru/connect.js";
//        js.onload = js.onreadystatechange = function () {
//        if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
//            if (!this.executed) {
//                this.executed = true;
//                setTimeout(function () {
//                    OK.CONNECT.insertShareWidget(id,did,st);
//                }, 0);
//            }
//        }};
//        d.documentElement.appendChild(js);        
    }
    
    function refresh_links ( data ) {
        var next = null, prev = null;
        
        if ( (next = jQuery ('.ajp-next-link')) && next.hasOwnProperty('0') ) {
            next = jQuery ( next[0] );
        }
        
        if ( (prev = jQuery ('.ajp-prev-link')) && prev.hasOwnProperty('0') ) {
            prev = jQuery ( prev[0] );
        }
        
        if ( next && data.hasOwnProperty('next_link') && data.next_link ) {
            next.attr( 'href', data.next_link );
            next.fadeIn ();
            if ( next.hasClass ('hidden') ) {
                next.removeClass ('hidden');    
            }
            
        } else if ( next && !data.next_link ) {
            next.fadeOut ();
        }
        
        if ( prev && data.hasOwnProperty('previous_link') && data.previous_link ) {
            prev.attr ( 'href', data.previous_link );
            prev.fadeIn ();
            if ( prev.hasClass ('hidden') ) {
                prev.removeClass ('hidden');    
            }
        } else if ( prev && !data.previous_link ) {
            prev.fadeOut ();
        }
    }
    
    jQuery ('.ajp-categories a').on( 'click', function () {        
        var data = send_request ( this );
        
        return false;
    });
    
    jQuery ('body').on('click', '.ajp-post-navigation a', function () {
        var data = send_request ( this );
        
        return false;
    });
    
});