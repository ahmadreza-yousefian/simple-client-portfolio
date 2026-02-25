<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Start of Elementor widget class
class SCP_Clients_Grid_Widget extends \Elementor\Widget_Base {

    // Widget Name
    public function get_name() {
        return 'scp-clients-grid';
    }

    // Widget Title
    public function get_title() {
        // This is the strategic name change we discussed.
        return 'Simple Clients Grid';
    }

    // Widget Icon
    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    // Widget Categories
    public function get_categories() {
        return ['scp-widgets']; // Our custom category ID
    }

    // --- Controls Section ---
    protected function _register_controls() {
        // --- Content Tab ---
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Query Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Get all "Industry" terms for the dropdown
        $industries = get_terms(['taxonomy' => 'scp_industry', 'hide_empty' => false]);
        $industry_options = ['' => 'All Industries'];
        foreach ($industries as $industry) {
            $industry_options[$industry->term_id] = $industry->name;
        }

        $this->add_control(
            'selected_industry',
            [
                'label' => 'Filter by Industry',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $industry_options,
                'default' => '',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => 'Number to Display',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1,
                'default' => 6,
                'description' => 'Enter -1 to show all.',
            ]
        );

        $this->end_controls_section();
        
        // --- Style Tab ---
        $this->start_controls_section(
            'section_style',
            [
                'label' => 'Style Settings',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
                'selectors' => [
                    '{{WRAPPER}} .clients-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // --- Render Section ---
    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'scp_client',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'title',
            'order' => 'ASC',
        ];

        if (!empty($settings['selected_industry'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'scp_industry',
                    'field' => 'term_id',
                    'terms' => $settings['selected_industry'],
                ],
            ];
        }

        $clients_query = new \WP_Query($args);

        if ($clients_query->have_posts()) :
            echo '<div class="clients-grid">';
            while ($clients_query->have_posts()) : $clients_query->the_post();
                $website_url = get_post_meta(get_the_ID(), '_scp_website_url', true);
                ?>
                <div class="client-item">
                    <?php if ($website_url) : ?><a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php endif; ?>
                    <?php if ($website_url) : ?></a><?php endif; ?>
                </div>
                <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
        else :
            echo '<p>No clients found to display.</p>';
        endif;
    }
    
    // This is a simple styling for the grid. A user can override this in their theme.
    protected function _content_template() {
        ?>
        <style>
            .clients-grid { display: grid; gap: 20px; align-items: center; }
            .client-item { text-align: center; }
            .client-item img { max-width: 100%; height: auto; opacity: 0.7; transition: opacity 0.3s ease; }
            .client-item a:hover img { opacity: 1; }
        </style>
        <?php
    }
}
