<?php
function cotufas2_theme_setup() {
    add_theme_support("title-tag");
    add_theme_support("custom-logo");

    // Register primary navigation menu location
    register_nav_menu("primary", __("Menú Principal", "cotufas2-theme"));
}
add_action("after_setup_theme", "cotufas2_theme_setup");

function cotufas2_enqueue_styles() {
    // Parent style
    wp_enqueue_style(
        "twentytwentyfive-style",
        get_template_directory_uri() . "/style.css",
        array(),
        wp_get_theme("twentytwentyfive")->get("Version")
    );
    
    // Child style (replaces inline CSS)
    wp_enqueue_style(
        "cotufas2-theme-style",
        get_stylesheet_uri(),
        array("twentytwentyfive-style"),
        wp_get_theme()->get("Version")
    );
}
add_action("wp_enqueue_scripts", "cotufas2_enqueue_styles");

function cotufas2_enqueue_rr_styles() {
    if ( is_singular() && has_shortcode( get_post()->post_content, "rr_reservation_form" ) ) {
        wp_enqueue_style(
            "cotufas2-rr-override",
            get_stylesheet_directory_uri() . "/rr-override.css",
            array("restaurant-reservations-frontend"),
            wp_get_theme()->get("Version")
        );
    }
}
add_action("wp_enqueue_scripts", "cotufas2_enqueue_rr_styles", 20);
?>
