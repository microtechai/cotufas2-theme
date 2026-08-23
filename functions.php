<?php
function cotufas2_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'cotufas2_theme_setup');

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
