<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/admin
 * @author     Maik Schmaddebeck <maik.schmaddebeck@icloud.com>
 */
class Event_Showcase_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * The custom meta fields of the custom post type product
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $version    The custom meta fields of the custom post type product
     */
    private $custom_meta_fields_product;

    /**
     * The custom meta fields of the custom post type date
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $version    The custom meta fields of the custom post type date
     */
    private $custom_meta_fields_date;

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_Showcase_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_Showcase_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/product-showcase-admin.css', array(), $this->version, 'all' );
        wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
        wp_enqueue_style('jquery-ui');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Product_Showcase_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Product_Showcase_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/product-showcase-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'jquery-ui-datepicker' );

	}




}
