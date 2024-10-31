<?php

class Custom_Post_Type {

	/**
	 * The singular description of the custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $singular    The singular description of the custom post type
	 */
	protected $singular;

	/**
	 * The plural description of the custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plural    The plural description of the custom post type
	 */
	protected $plural;

	/**
	 * The custom meta fields of the custom post type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $custom_meta_fields    The custom meta fields of the custom post type
	 */
	protected $custom_meta_fields;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $singular       The singular description of the custom post type
	 * @param      string    $plural    The plural description of the custom post type
	 */
	public function __construct( $singular, $plural ) {

		$this->singular = $singular;
		$this->plural = $plural;

	}


	/**
	 * @param $post_id
	 * @param $nonce
	 *
	 * @since 1.0.0
	 */
	protected function get_save_post($post_id, $nonce, $custom_meta_fields) {

		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], basename(__FILE__)) ? true : false;

		if ($is_autosave || $is_revision || !$is_valid_nonce) :
			return;
		endif;

		// loop through fields and save the data
		foreach ($custom_meta_fields as $field) :
			$old = get_post_meta($post_id, $field['id'], true);
			$new = isset($_POST[$field['id']])? $_POST[$field['id']] : false;

			if ($new && $new != $old) :
				update_post_meta($post_id, $field['id'], sanitize_text_field($new));
			elseif ('' == $new && $old) :
				delete_post_meta($post_id, $field['id'], $old);
			endif;
		endforeach;

	}

	/**
	 * @param $nonce
	 * @param $custom_meta_fields
	 *
	 * @since 1.0.0
	 */
	protected function get_metabox_callback($nonce, $custom_meta_fields) {

		global $post;
		wp_nonce_field(basename(__FILE__), $nonce);

		// Begin the field table and loop
		echo '<table class="form-table">';
		foreach ($custom_meta_fields as $field) :
			// get value of this field if it exists for this post
			$meta = get_post_meta($post->ID, $field['id'], true);
			// begin a table row with
			echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
			switch($field['type']) :
				case 'text':
					echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                            <br /><span class="description">'.$field['desc'].'</span>';
					break;
				case 'number':
					echo '<input type="number" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                            <br /><span class="description">'.$field['desc'].'</span>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
					break;
				case 'select':
					echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
					}
					echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;
				case 'date-picker':
					echo '<input type="date" name="'. $field['id'] . '" value="'.$meta.'">';

					break;
			endswitch;
			echo '</td></tr>';
		endforeach;
		echo '</table>'; // end table

	}

}