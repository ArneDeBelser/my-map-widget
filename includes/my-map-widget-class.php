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

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $data_source = $settings['data_source'];
        $data = '';

        if ($data_source === 'local') {
            $local_data = $settings['local_data'];

            if ($local_data && !empty($local_data['url'])) {
                $data = file_get_contents($local_data['url']);
            }
        } elseif ($data_source === 'remote') {
            $remote_data = $settings['remote_data'];

            if (!empty($remote_data)) {
                $data = file_get_contents($remote_data);
            }
        }

        if (!empty($data)) {
?>
            <style>
                #my-map-widget-map {
                    height: <?php echo $settings['map_height'] . 'px'; ?>;
                    min-height: 100px;
                }
            </style>
            <div id="my-map-widget-map"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var map = L.map('my-map-widget-map').setView([0, 0], 2);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var heatmapData = [];

                    var data = <?php echo json_encode($data); ?>;

                    Papa.parse(data, {
                        header: true,
                        dynamicTyping: true,
                        complete: function(results) {
                            var heatmapData = [];

                            if (results.errors.length > 0) {
                                console.error('CSV parsing error', results.errors);
                            } else {
                                results.data.forEach(function(row) {
                                    var lat = parseFloat(row.lat);
                                    var lng = parseFloat(row.lng);

                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        heatmapData.push([lat, lng]);
                                    }
                                });

                                if (heatmapData.length > 0) {
                                    var heatmapLayer = L.heatLayer(heatmapData, {
                                        radius: 30,
                                        blur: 20,
                                        gradient: {
                                            0.4: 'blue',
                                            0.6: 'cyan',
                                            0.7: 'lime',
                                            0.8: 'yellow',
                                            1.0: 'red',
                                        },
                                        minOpacity: 0.9
                                    }).addTo(map);
                                }
                            }
                        }
                    })
                });
            </script>
        <?php
        }
    }

    protected function _content_template()
    {
        ?>
        <div id="my-map-widget-map" style="height: 400px;"></div>
<?php
    }
}
