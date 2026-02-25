<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// We need to "use" these classes to access Typography and Color controls
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;

class SCP_Client_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'scp-client-title';
    }

    public function get_title() {
        // This is the strategic name change we discussed.
        return 'Client Title (Single/Loop)';
    }

    public function get_icon() {
        return 'eicon-post-title';
    }

    public function get_categories() {
        return ['scp-widgets'];
    }

    protected function _register_controls() {
        // --- Style Tab ---
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => 'Title Style',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Control for Alignment
        $this->add_responsive_control(
            'title_align',
            [
                'label' => 'Alignment',
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => ['title' => 'Left', 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => 'Center', 'icon' => 'eicon-text-align-center'],
                    'right' => ['title' => 'Right', 'icon' => 'eicon-text-align-right'],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .scp-client-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Control for Text Color
        $this->add_control(
            'title_color',
            [
                'label' => 'Text Color',
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .scp-client-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Control for Typography (Font, Size, Weight, etc.)
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .scp-client-title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if (is_singular('scp_client') || in_the_loop()) {
            // We are just outputting the title. Elementor will handle the styles.
            the_title('<h1 class="scp-client-title">', '</h1>');
        } else {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<h3>Client Title</h3><p>This widget should be used within a "Single" or "Loop" template for clients.</p>';
            }
        }
    }
}
