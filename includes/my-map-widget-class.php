<?php

if (!defined('ABSPATH')) {
    exit;
}

class My_Map_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'my-map-widget';
    }

    public function get_title()
    {
        return __('My Map Widget', 'my-map-widget');
    }

    public function get_icon()
    {
        return 'eicon-google-maps';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'data_section',
            [
                'label' => __('Data', 'my-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'data_source',
            [
                'label' => __('Data Source', 'my-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'local' => __('Local', 'my-map-widget'),
                    'remote' => __('Remote', 'my-map-widget'),
                ],
                'default' => 'local',
            ]
        );

        $this->add_control(
            'local_data',
            [
                'label' => __('Local Data', 'my-map-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_type' => 'csv',
                'condition' => [
                    'data_source' => 'local',
                ]
            ]
        );

        $this->add_control(
            'remote_data',
            [
                'label' => __('Remote Data URL', 'my-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [
                    'data_source' => 'remote',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'map_section',
            [
                'label' => __('Map', 'my-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'map_height',
            [
                'label' => __('Map Height', 'my-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '400',
                'min' => '100',
                'description' => __('Enter the map height in pixels. Minimum value is 100 pixels.', 'my-map-widget'),
            ]
        );

        $this->end_controls_section();
    }
}
