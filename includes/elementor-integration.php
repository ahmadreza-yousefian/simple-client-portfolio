<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Elementor Integration Class
 *
 * This class is responsible for registering widget categories and the widgets themselves.
 *
 * @since 1.2.0
 */
final class SCP_Elementor_Integration {

    /**
     * @var SCP_Elementor_Integration The single instance of the class
     */
    private static $_instance = null;

    /**
     * Main SCP_Elementor_Integration Instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        // Hook to register our custom widget category
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_category'));
        // Hook to register our widgets
        add_action('elementor/widgets/register', array($this, 'register_widgets'));
    }

    /**
     * Add a custom category to the Elementor widget panel.
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function add_elementor_widget_category($elements_manager) {
        $elements_manager->add_category(
            'scp-widgets', // Unique category ID
            [
                'title' => 'Client Widgets', // Category title
                'icon'  => 'eicon-person', // Category icon
            ]
        );
    }

    /**
     * Register our custom widgets.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager
     */
    public function register_widgets($widgets_manager) {
        // --- Widget: Clients Grid ---
        require_once SCP_PLUGIN_DIR . 'widgets/clients-grid-widget.php';
        $widgets_manager->register(new \SCP_Clients_Grid_Widget());

        // --- Widget: Client Title ---
        require_once SCP_PLUGIN_DIR . 'widgets/client-title-widget.php';
        $widgets_manager->register(new \SCP_Client_Title_Widget());

        // --- Widget: Client Logo ---
        require_once SCP_PLUGIN_DIR . 'widgets/client-logo-widget.php';
        $widgets_manager->register(new \SCP_Client_Logo_Widget());

        // --- Widget: Client Data ---
        require_once SCP_PLUGIN_DIR . 'widgets/client-data-widget.php';
        $widgets_manager->register(new \SCP_Client_Data_Widget());
    }
}

// Instantiate our integration class
SCP_Elementor_Integration::instance();
