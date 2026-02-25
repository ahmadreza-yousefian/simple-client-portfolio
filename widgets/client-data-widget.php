<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class SCP_Client_Data_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'scp-client-data';
    }

    public function get_title() {
        return 'Client Data (Single/Loop)';
    }

    public function get_icon() {
        return 'eicon-database';
    }

    public function get_categories() {
        return ['scp-widgets'];
    }

    protected function _register_controls() {
        // --- Content Tab: Data Source ---
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Data Selection',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'data_source',
            [
                'label' => 'Which data to display?',
                'type' => Controls_Manager::SELECT,
                'default' => 'website_url',
                'options' => [
                    'website_url' => 'Website URL',
                    'industry' => 'Industry (Category)',
                ],
            ]
        );

        $this->end_controls_section();

        // --- Style Tab ---
        $this->start_controls_section(
            'section_style',
            [
                'label' => 'Style',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .scp-client-data, {{WRAPPER}} .scp-client-data a',
            ]
        );
        
        $this->add_control(
            'link_color',
            [
                'label' => 'Link Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .scp-client-data a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        // Check if we are in a valid context (single or loop)
        if (!is_singular('scp_client') && !in_the_loop()) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<h3>Client Data</h3><p>This widget should be used within a "Single" or "Loop" template for clients.</p>';
            }
            return;
        }
        
        $settings = $this->get_settings_for_display();
        $data_source = $settings['data_source'];
        $post_id = get_the_ID();
        $output = '';

        // Use a switch statement to render the correct data based on user's choice
        switch ($data_source) {
            case 'website_url':
                $website_url = get_post_meta($post_id, '_scp_website_url', true);
                if ($website_url) {
                    // Using a translation-ready string
                    $output = '<a href="' . esc_url($website_url) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Visit Website', 'simple-client-portfolio') . '</a>';
                }
                break;

            case 'industry':
                $terms = get_the_terms($post_id, 'scp_industry');
                if ($terms && !is_wp_error($terms)) {
                    $industry_links = [];
                    foreach ($terms as $term) {
                        $industry_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                    }
                    $output = implode(', ', $industry_links);
                }
                break;
        }

        if (!empty($output)) {
            echo '<div class="scp-client-data">' . $output . '</div>';
        }
    }
}
