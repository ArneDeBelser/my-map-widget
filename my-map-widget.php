<?php
/*
Plugin Name: My Map Widget
Plugin URI: https://www.arnedebleser.be
Description: A custom Elementor widget that displays a Google Maps heatmap based on user data
Version: 1.0.0
Author: De Belser Arne
Author URI: https://www.arnedebleser.be
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

function my_map_widget_enqueue_scripts()
{
    wp_register_script('leaflet', 'https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js', [], null, true);
    wp_enqueue_script('leaflet');

    wp_register_script('leaflet-heat', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.heat/0.2.0/leaflet-heat.js', ['leaflet'], null, true);
    wp_enqueue_script('leaflet-heat');

    wp_register_script('papaparse', 'https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js', [], null, true);
    wp_enqueue_script('papaparse');
}
add_action('wp_enqueue_scripts', 'my_map_widget_enqueue_scripts');

function my_map_widgets_enqueue_styles()
{
    wp_register_style('leaflet', 'https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css', [], null);
    wp_enqueue_style('leaflet');
}
add_action('wp_enqueue_scripts', 'my_map_widgets_enqueue_styles');

add_action('elementor/widgets/widgets_registered', function () {
    require_once plugin_dir_path(__FILE__) . 'includes/my-map-widget-class.php';

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \My_Map_Widget());
}, 999);
