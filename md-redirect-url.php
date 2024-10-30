<?php
/*
Plugin Name: MD Redirect URL
Plugin URI: http://mobidevices.ru
Description: Плагин для автоматического 301 редиректа неверных и старых URL на правильные и новые, а также изменение адреса поиска с <b>site.com/?s=ищу</b> на <b>site.com/search/ищу</b>, разработанный порталом <a href="http://mobidevices.ru">MobiDevices</a>.
Version: 1.0
Author: MobiDevices
Author URI: http://mobidevices.ru
Author Email: vadizar@mobidevices.ru
*/

function md_redirect(){
global $post;
if(is_search() && ! empty($_GET['s'])){
if(get_query_var('paged')){wp_redirect(home_url("/search/").urlencode( get_query_var('s')).("/page/").get_query_var('paged'),301);exit();}
else{wp_redirect(home_url("/search/").urlencode(get_query_var('s')),301);exit();}
}
elseif(is_single()){
    $post_url=get_permalink($post->ID);
    if($post_url!='http://mobidevices.ru'.strtolower($_SERVER['REQUEST_URI'])){
        wp_redirect($post_url,301);exit;
    }
}
}
add_action('template_redirect','md_redirect');

function md_404() {
    global $wpdb;
    if(!is_404())
        return;
    $slug = htmlspecialchars( basename( $_SERVER['REQUEST_URI'] ) );
    $id = $wpdb->get_var( 
        $wpdb->prepare( "
        SELECT ID 
        FROM $wpdb->posts
        WHERE post_name = '%s'
        AND post_status = 'publish'
        ", $slug )
        );

    if ($id) {
        $url = get_permalink( $id );
        header( 'HTTP/1.1 301 Moved Permanently' );
        header( 'Location: ' . $url );
    } else {
        return true;
    }
}
add_action('template_redirect','md_404');
