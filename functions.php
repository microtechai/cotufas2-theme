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

/* ======================================================
   SEO: Schema.org JSON-LD for LocalBusiness
   ====================================================== */
function cotufas2_schema_json_ld() {
    if ( is_singular() ) {
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Restaurant',
            'name'        => 'Cotufas 2',
            'description' => 'Pizza artesanal argentina con masa madre y horneada en piedra en Santa Cruz de Tenerife',
            'url'         => home_url('/'),
            'telephone'   => '+34922654822',
            'priceRange'  => '€€',
            'servesCuisine' => array('Argentine', 'Pizza', 'Italian'),
            'acceptsReservations' => 'True',
            'menu'        => home_url('/carta/'),
            'sameAs'      => array(
                'https://www.instagram.com/cotufas2/',
            ),
            'address'     => array(
                '@type'       => 'PostalAddress',
                'streetAddress' => 'Calle San Antonio, 48',
                'addressLocality' => 'Santa Cruz de Tenerife',
                'postalCode'  => '38203',
                'addressCountry' => 'ES',
            ),
            'openingHoursSpecification' => array(
                array(
                    '@type'           => 'OpeningHoursSpecification',
                    'dayOfWeek'       => array(
                        'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
                    ),
                    'opens'           => '12:00',
                    'closes'          => '23:30',
                ),
            ),
            'image'         => home_url('/wp-content/uploads/2026/08/Pizza-tenerife.webp'),
        );

        // Add geo coordinates if available
        $schema['geo'] = array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => 28.4636,
            'longitude' => -16.2518,
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'cotufas2_schema_json_ld', 1);

/* ======================================================
   SEO: Open Graph & Twitter Card Meta Tags
   ====================================================== */
function cotufas2_open_graph_meta() {
    if ( is_singular() ) {
        $title       = get_the_title();
        $description = get_the_excerpt();
        $image_url   = home_url('/wp-content/uploads/2026/08/Pizza-tenerife.webp');
        
        // Try to get featured image first
        if ( has_post_thumbnail() ) {
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            if ( $thumbnail ) {
                $image_url = $thumbnail[0];
            }
        }
        
        echo '<meta property="og:site_name" content="Cotufas 2" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url($image_url) . '" />' . "\n";
        echo '<meta property="og:locale" content="es_ES" />' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($image_url) . '" />' . "\n";
    }
}
add_action('wp_head', 'cotufas2_open_graph_meta', 2);

/* ======================================================
   SEO: Favicon & Touch Icons
   ====================================================== */
function cotufas2_favicon() {
    echo '<link rel="icon" href="' . esc_url(home_url('/wp-content/uploads/2026/08/logo-32x32.png')) . '" type="image/png">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url(home_url('/wp-content/uploads/2026/08/logo.png')) . '">' . "\n";
    echo '<meta name="theme-color" content="#1A1A1A">' . "\n";
}
add_action('wp_head', 'cotufas2_favicon', 3);

/* ======================================================
   PERFORMANCE: Preload critical fonts & images
   ====================================================== */
function cotufas2_preload_resources() {
    // Preload Playfair Display for hero text
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/playfairdisplay/v34/NJ4vx孟pJMqg1Grxg7SNF8cGOiAOu75qxcVXc0vDA.ttf" as="font" type="font/ttf" crossorigin>' . "\n";
    // Preload hero image
    if ( is_front_page() ) {
        echo '<link rel="preload" as="image" href="' . esc_url(home_url('/wp-content/uploads/2026/08/Pizza-tenerife.webp')) . '">' . "\n";
    }
}
add_action('wp_head', 'cotufas2_preload_resources', 4);

/* ======================================================
   HEADER: Scroll shrink + opening status
   ====================================================== */
function cotufas2_header_scripts() {
    ?>
    <script>
    (function() {
        'use strict';
        var header = document.querySelector('.site-header.is-sticky');
        if (!header) return;

        /* --- Scroll shrink --- */
        var scrolled = false;
        function onScroll() {
            var didScroll = window.scrollY > 60;
            if (didScroll !== scrolled) {
                scrolled = didScroll;
                header.classList.toggle('is-scrolled', scrolled);
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        /* --- Opening status --- */
        var el = document.querySelector('.opening-status');
        if (el) {
            var openTime = 12, closeTime = 23.5; // 23:30
            var now = new Date();
            var hours = now.getHours() + now.getMinutes() / 60;
            // Handle overnight closure (open 12:00, close next day 23:30)
            var isOpen = hours >= openTime && hours < closeTime;
            el.classList.add(isOpen ? 'is-open' : 'is-closed');
            el.setAttribute('aria-label', isOpen ? 'Abierto ahora' : 'Cerrado');
            if (isOpen) {
                el.textContent = 'Abierto ahora';
            } else {
                el.textContent = 'Cerrado';
            }
        }
    })();
    </script>
    <?php
}
add_action('wp_footer', 'cotufas2_header_scripts', 100);

/* ======================================================
   MOBILE MENU — Staggered animation trigger
   ====================================================== */
function cotufas2_mobile_menu_animation() {
    ?>
    <script>
    (function() {
        'use strict';
        var btns = document.querySelectorAll('.wp-block-navigation__responsive-container-open');
        btns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var container = document.querySelector('.wp-block-navigation__responsive-container');
                if (!container) return;
                var isOpen = container.classList.contains('is-menu-open');
                var items = container.querySelectorAll('.wp-block-navigation-item');
                items.forEach(function(item) {
                    item.classList.remove('animating');
                    item.style.animationDelay = '';
                    item.style.opacity = '';
                    item.style.transform = '';
                });
                if (isOpen) {
                    container.classList.remove('animating');
                    /* Force reflow */
                    void container.offsetHeight;
                    setTimeout(function() {
                        items.forEach(function(item, i) {
                            item.style.setProperty('--stagger', i);
                            item.classList.add('animating');
                        });
                    }, 10);
                }
            });
        });
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                if (m.type === 'attributes') {
                    var container = m.target;
                    var isOpen = container.classList.contains('is-menu-open');
                    var items = container.querySelectorAll('.wp-block-navigation-item');
                    if (isOpen) {
                        items.forEach(function(item) {
                            item.classList.remove('animating');
                            item.style.animationDelay = '';
                            item.style.opacity = '';
                            item.style.transform = '';
                        });
                        void container.offsetHeight;
                        setTimeout(function() {
                            items.forEach(function(item, i) {
                                item.style.setProperty('--stagger', i);
                                item.classList.add('animating');
                            });
                        }, 10);
                    } else {
                        items.forEach(function(item) {
                            item.classList.remove('animating');
                            item.style.animationDelay = '';
                            item.style.opacity = '';
                            item.style.transform = '';
                        });
                    }
                }
            });
        });
        var mc = document.querySelector('.wp-block-navigation__responsive-container');
        if (mc) observer.observe(mc, { attributes: true, attributeFilter: ['class'] });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'cotufas2_mobile_menu_animation', 101);

?>
