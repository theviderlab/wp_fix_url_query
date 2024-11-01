<?php
/*
Plugin Name: Maintain Query Pin
Description: Maintains the `pin` parameter in the URL as the user navigates the site, replacing it if it already exists.
Version: 1.3
Author: ViderLab
Author URI: https://viderlab.com
License: GPL2
*/

// Function to add or replace the `pin` parameter in internal links
function add_replace_pin_links($content) {
    // Check if the `pin` parameter is in the current URL
    if (isset($_GET['pin'])) {
        $pin = sanitize_text_field($_GET['pin']);
        
        // Replace or add the `pin` parameter in all internal links within the content
        $content = preg_replace_callback(
            '/href=["\'](https?:\/\/[^"\']+)["\']/i',
            function($matches) use ($pin) {
                $url = html_entity_decode($matches[1]);
                
                // Remove existing `pin` parameter before adding the new value
                $url = remove_query_arg('pin', $url);
                $url = add_query_arg('pin', $pin, $url);
                
                return 'href="' . esc_url($url) . '"';
            },
            $content
        );
    }
    return $content;
}

// Apply the function to all areas where links are generated
add_filter('the_content', 'add_replace_pin_links');
add_filter('widget_text', 'add_replace_pin_links');
add_filter('widget_text_content', 'add_replace_pin_links');
add_filter('wp_nav_menu_items', 'add_replace_pin_links');
add_filter('widget_nav_menu_args', 'add_replace_pin_links');

// Apply `pin` in automatically generated links
function add_replace_pin_auto_links($url) {
    if (!is_admin() && isset($_GET['pin'])) {
        $pin = sanitize_text_field($_GET['pin']);
        
        // Remove existing `pin` parameter before adding the new value
        $url = html_entity_decode(remove_query_arg('pin', $url));
        $url = add_query_arg('pin', $pin, $url);
    }
    return $url;
}
add_filter('home_url', 'add_replace_pin_auto_links');
add_filter('post_link', 'add_replace_pin_auto_links');
add_filter('page_link', 'add_replace_pin_auto_links');
add_filter('post_type_link', 'add_replace_pin_auto_links');
add_filter('attachment_link', 'add_replace_pin_auto_links');
add_filter('category_link', 'add_replace_pin_auto_links');
add_filter('tag_link', 'add_replace_pin_auto_links');
add_filter('author_link', 'add_replace_pin_auto_links');
add_filter('day_link', 'add_replace_pin_auto_links');
add_filter('month_link', 'add_replace_pin_auto_links');
add_filter('year_link', 'add_replace_pin_auto_links');