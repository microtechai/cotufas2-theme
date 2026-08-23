<?php
function cotufas2_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'cotufas2_theme_setup');

function cotufas2_enqueue_styles() {
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('twentytwentyfive')->get('Version')
    );
    wp_enqueue_style(
        'cotufas2-style',
        get_stylesheet_uri(),
        array('twentytwentyfive-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'cotufas2_enqueue_styles');

function cotufas2_debug_marker() {
    echo "\n<!-- COTUFAS2-THEME-ACTIVE-1.0 -->\n";
}
add_action('wp_head', 'cotufas2_debug_marker', 0);

function cotufas2_inline_css() {
?>
<style id="cotufas2-theme-css">
body { background-color: #1A1A1A !important; color: #F5F0E8 !important; }
.wp-block-navigation a { color: #F5F0E8 !important; }
a { color: #F5A623; }
</style>
<?php
}
add_action('wp_head', 'cotufas2_inline_css', 100);
?>
