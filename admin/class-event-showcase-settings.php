<?php

/**
 * The settings-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/admin
 */

/**
 * The settings-specific functionality of the plugin.
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/admin
 * @author     Maik Schmaddebeck <maik.schmaddebeck@icloud.com>
 */
class Event_Showcase_Settings_Page
{

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Add options page to the CPT submenu
     *
     * @since 1.0.0
     */
    public function add_settings_page()
    {
        add_submenu_page(
            'edit.php?post_type=es_event',
            __( 'Einstellungen', 'event-showcase' ),
            __( 'Einstellungen', 'event-showcase' ),
            'manage_options',
            'event-showcase-settings',
            array($this, 'create_settings_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_settings_page()
    {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // check if the user have submitted the settings
        // wordpress will add the "settings-updated" $_GET parameter to the url
        if ( isset( $_GET['settings-updated'] ) ) {
            // add settings saved message with the class of "updated"
            add_settings_error( 'event-showcase_messages', 'event-showcase_message', __( 'Einstellungen gespeichert', 'event-showcase' ), 'updated' );
        }

        // show error/update messages
        settings_errors( 'event-showcase_messages' );

        // Set class property
        $this->options = get_option( 'event_showcase' );
        ?>
        <div class="wrap">
            <h1>Einstellungen für Strickkurse</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'event_showcase_option_group' );
                do_settings_sections( 'event-showcase-settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Set up the settings page
     *
     * @since 1.0.0
     */
    public function settings_init() {
        // register a new setting for "product-showcase" page
        register_setting( 'event_showcase_option_group', 'event_showcase' );

        // register a new section in the "product-showcase" page
        add_settings_section(
            'registration_page_section',
            __( 'Die Seite mit dem Shortcode zum Anmelden für die Kurse', 'event-showcase' ),
            array($this, 'registration_page_cb'),
            'event-showcase-settings'
        );

        /*
         * Settings field for the >Showcase Page<
         */
        add_settings_field(
            'showcase_page_field',
            __( 'Die Seite zum <b>Auswählen</b> eines Kurses', 'event-showcase' ),
            array($this, 'page_field_cb'),
            'event-showcase-settings',
            'registration_page_section',
            [
                'label_for' => 'showcase_page'
            ]
        );

        /*
         * Settings field for the >Registration Page<
         */
        add_settings_field(
            'registration_page_field',
            __( 'Die Seite zum <b>Anmelden</b> für die Kurse', 'event-showcase' ),
            array($this, 'page_field_cb'),
            'event-showcase-settings',
            'registration_page_section',
            [
                'label_for' => 'registration_page'
            ]
        );

	    /*
		 * Settings field for the >Terms and Conditions Page<
		 */
        add_settings_field(
            'tc_page_field',
            __( 'Deine AGB Seite', 'event-showcase' ),
            array($this, 'page_field_cb'),
            'event-showcase-settings',
            'registration_page_section',
            [
                'label_for' => 'tc_page'
            ]
        );

	    /*
		 * Settings field for the >Privacy Page<
		 */
        add_settings_field(
            'privacy_page_field',
            __( 'Deine Datenschutz Seite', 'event-showcase' ),
            array($this, 'page_field_cb'),
            'event-showcase-settings',
            'registration_page_section',
            [
                'label_for' => 'privacy_page'
            ]
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return the sanitized input
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function registration_page_cb()
    {
        print '';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function page_field_cb( $args )
    {
        wp_dropdown_pages(
            array(
                'name' => 'event_showcase['. $args['label_for'] . ']',
                'selected' => $this->options[ $args['label_for'] ]
            )
        );
    }

}