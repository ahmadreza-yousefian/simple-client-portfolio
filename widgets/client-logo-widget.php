<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class SCP_Client_Logo_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'scp-client-logo';
    }

    public function get_title() {
        // This is the strategic name change we discussed.
        return 'Client Logo (Single/Loop)';
    }

    public function get_icon() {
        return 'eicon-image';
    }

    public function get_categories() {
        return ['scp-widgets'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Logo Settings',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => 'Link To',
                'type' => Controls_Manager::SELECT,
                'default' => 'post',
                'options' => [
                    'post' => 'Client Page',
                    'website' => 'Client Website',
                    'none' => 'None',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail', 
                'default' => 'medium_large',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // This widget is designed for Single Client templates or Loop Grids
        if (is_singular('scp_client') || in_the_loop()) {
            $settings = $this->get_settings_for_display();
            
            $link_url = false;
            switch ($settings['link_to']) {
                case 'post':
                    $link_url = get_permalink();
                    break;
                case 'website':
                    $link_url = get_post_meta(get_the_ID(), '_scp_website_url', true);
                    break;
            }

            if ($link_url) {
                echo '<a href="' . esc_url($link_url) . '" ' . ($settings['link_to'] === 'website' ? 'target="_blank" rel="noopener noreferrer"' : '') . '>';
            }

            if (has_post_thumbnail()) {
                // Get image with the size selected by the user
                the_post_thumbnail($settings['thumbnail_size']);
            }

            if ($link_url) {
                echo '</a>';
            }

        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<h3>Client Logo</h3><p>This widget should be used within a "Single" or "Loop" template for clients.</p>';
            }
        }
    }
}
