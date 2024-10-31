<?php


class Event_Showcase_Event_Custom_Post_Type extends Custom_Post_Type {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $singular  The singular description of the custom post type
	 * @param      string    $plural    The plural description of the custom post type
	 */
	public function __construct() {

		$singular = 'Strickkurs';
		$plural = 'Strickkurse';

		parent::__construct( $singular, $plural );

	}

	/**
	 * Register the Custom Post Type for the Products
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$singular = $this->singular;
		$plural = $this->plural;

		$labels = array (
			'name'               => $plural,
			'singular_name'      => $singular,
			'add_new'            => sprintf('%s anlegen', $singular),
			'add_new_item'       => sprintf('Neuen %s anlegen', $singular),
			'edit_item'          => sprintf('%s bearbeiten', $singular),
			'new_item'           => sprintf('Neuer %s', $singular),
			'all_items'          => sprintf('Alle %s', $plural),
			'view'               => sprintf('%s anzeigen', $singular),
			'view_item'          => sprintf('%s anzeigen', $singular),
			'search_term'        => sprintf('%s suchen', $plural),
			'not_found'          => sprintf('Keinen %s gefunden', $singular),
			'not_found_in_trash' => sprintf('Keinen %s im Papierkorb gefunden', $singular)
		);

		$args = array(
			'public'    => true,
			'labels'    => $labels,
			'label'     => $singular,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => false,
			'menu_position' => 100,
			'menu_icon' => 'dashicons-calendar',
			'can_export' => true,
			'delete_with_user' => false,
			'hierarchical' => false,
			'has_archive' => true,
			'query_var' => true,
			'capability_type' => 'page',
			'map_meta_cap' => true,
			'supports' => array(
				'title', 'thumbnail', 'editor'
			)
		);

		// Field Array
		$prefix = 'event_';
		$this->custom_meta_fields = array(
			array(
				'label'=> __('Preis', 'event-showcase'),
				'desc'  => __('Was kostet die Teilnahme am Strickkurs?', 'event-showcase'),
				'id'    => $prefix . 'price',
				'type'  => 'text'
			),
			array(
				'label'=> __('Keine Anmeldung nötig', 'event-showcase'),
				'desc'  => __('', 'event-showcase'),
				'id'    => $prefix . 'register',
				'type'  => 'checkbox'
			)
		);

		register_post_type('es_event', $args);
	}

	/**
	 * Add a metabox for all the fields of the custom post type
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'es_event_metabox',
			__( 'Allgemeine Informationen', 'event-showcase' ),
			array($this, 'metabox_callback'),
			'es_event',
			'normal',
			'high'
		);
	}

	/**
	 * Get the HTML for the metabox
	 *
	 * @since 1.0.0
	 */
	public function metabox_callback() {

		$this->get_metabox_callback( 'es_event_nonce', $this->custom_meta_fields );

	}

	/**
	 * @param int $post_id The post ID.
	 *
	 * @since 1.0.0
	 */
	public function save_post($post_id) {

		$this->get_save_post( $post_id, 'es_event_nonce', $this->custom_meta_fields );

	}

}