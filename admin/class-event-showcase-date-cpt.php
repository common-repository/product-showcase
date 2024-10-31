<?php

/**
 * Created by PhpStorm.
 * User: Smadback
 * Date: 01.03.2017
 * Time: 09:07
 */
class Event_Showcase_Date_Custom_Post_Type extends Custom_Post_Type {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $singular  The singular description of the custom post type
	 * @param      string    $plural    The plural description of the custom post type
	 */
	public function __construct() {

		$singular = __('Termin', 'class-showcase');
		$plural = __('Termine', 'class-showcase');

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
			'add_new_item'       => sprintf('Neue %s anlegen', $singular),
			'edit_item'          => sprintf('%s bearbeiten', $singular),
			'new_item'           => sprintf('Neue %s', $singular),
			'all_items'          => sprintf('Alle %s', $plural),
			'view'               => sprintf('%s anzeigen', $singular),
			'view_item'          => sprintf('%s anzeigen', $singular),
			'search_term'        => sprintf('%s suchen', $plural),
			'not_found'          => sprintf('Keine %s gefunden', $singular),
			'not_found_in_trash' => sprintf('Keine %s im Papierkorb gefunden', $singular)
		);

		$args = array(
			'public'    => true,
			'labels'    => $labels,
			'label'     => $singular,
			'show_in_menu' => 'edit.php?post_type=es_event',
			'can_export' => true,
			'delete_with_user' => false,
			'hierarchical' => false,
			'has_archive' => true,
			'query_var' => true,
			'capability_type' => 'page',
			'map_meta_cap' => true,
			'rewrite' => array(
				'slug' => 'es_date',
				'with_front' => true,
				'pages' => true,
				'feeds' => false
			),
			'supports' => array(
				'title'
			)
		);

		$query_args = array(
			'post_type'         => 'es_event',
			'post_status'       => 'publish',
			'no_found_rows'     => false,
			'order'             => 'ASC'
		);
		$query = new WP_Query($query_args);

		// Field Array
		$prefix = 'date_';
		$this->custom_meta_fields = array(
			'strickkurs' => array(
				'label'=> __('Strickkurs', 'event-showcase'),
				'desc'  => __('Für welchen Strickkurs soll der Termin angelegt werden?', 'event-showcase'),
				'id'    => $prefix . 'event',
				'type'  => 'select',
				'options' => array ()
			),
			'termin1' => array(
				'label'=> 'Erster Termin',
				'desc'  => __('Wann ist der <b>erste</b> Termin für den Strickkurs?', 'event-showcase'),
				'id'    => $prefix . 'first',
				'type'  => 'date-picker'
			),
			'termin2' => array(
				'label'=> 'Zweiter Termin',
				'desc'  => __('Wann ist der <b>zweite</b> Termin für den Strickkurs?', 'event-showcase'),
				'id'    => $prefix . 'second',
				'type'  => 'date-picker'
			)
		);

		if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
			array_push( $this->custom_meta_fields['strickkurs']['options'], array(
				'label' => get_the_title(),
				'value' => get_the_ID()
			) );
		endwhile; endif; wp_reset_postdata();

		register_post_type('es_date', $args);
	}

	/**
	 * Add a metabox for all the fields of the custom post type
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'es_date_metabox',
			__( 'Allgemeine Informationen', 'event-showcase' ),
			array($this, 'metabox_callback'),
			'es_date',
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

		$this->get_metabox_callback('es_date_nonce', $this->custom_meta_fields);

	}

	/**
	 * @param int $post_id The post ID.
	 *
	 * @since 1.0.0
	 */
	public function save_post($post_id) {

		$this->get_save_post($post_id, 'es_date_nonce', $this->custom_meta_fields);

	}

}