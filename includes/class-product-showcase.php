<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Product_Showcase
 * @subpackage Product_Showcase/includes
 * @author     Maik Schmaddebeck <maik.schmaddebeck@icloud.com>
 */
class Product_Showcase {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Product_Showcase_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $product_showcase    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'product-showcase';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Product_Showcase_Loader. Orchestrates the hooks of the plugin.
	 * - Product_Showcase_i18n. Defines internationalization functionality.
	 * - Product_Showcase_Admin. Defines all hooks for the admin area.
	 * - Product_Showcase_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product-showcase-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product-showcase-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-showcase-admin.php';

        /**
         * The class responsible for defining all actions for the setings page of the plugin
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-showcase-settings.php';

		/**
		 * The class responsible for defining all actions for the setings page of the plugin
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-custom-post-type.php';

		/**
		 * The class responsible for defining all actions for the event custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-showcase-event-cpt.php';

		/**
		 * The class responsible for defining all actions for the date custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-showcase-date-cpt.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-event-showcase-public.php';


		$this->loader = new Product_Showcase_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Product_Showcase_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Product_Showcase_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Event_Showcase_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/*
		 * Custom post type: Event
		 */
		$cpt_event = new Event_Showcase_Event_Custom_Post_Type();
		$this->loader->add_action( 'init', $cpt_event, 'register_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $cpt_event, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post_es_event', $cpt_event, 'save_post' );

		/*
		 * Custom post type: Date
		 */
		$cpt_date = new Event_Showcase_Date_Custom_Post_Type();
		$this->loader->add_action( 'init', $cpt_date, 'register_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $cpt_date, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post_es_date', $cpt_date, 'save_post' );

        /*
         * Plugin settings
         */
        $settings_page = new Event_Showcase_Settings_Page();
        $this->loader->add_action( 'admin_menu', $settings_page, 'add_settings_page' );
        $this->loader->add_action( 'admin_init', $settings_page, 'settings_init' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Event_Showcase_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_public, 'register_shortcode' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Product_Showcase_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
