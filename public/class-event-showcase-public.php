<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Product_Showcase
 * @subpackage Product_Showcase/public
 * @author     Your Name <email@example.com>
 */
class Event_Showcase_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/event-showcase-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/event-showcase-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the shortcodes for the product showcase plugin
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode() {

		add_shortcode( 'event_showcase', 'register_showcase_shortcode' );
		function register_showcase_shortcode( $atts, $content = null ) {
			$option = get_option( 'event_showcase' );

			$query_args = array(
				'post_type'     => 'es_event',
				'post_status'   => 'publish',
				'no_found_rows' => false,
				'order'         => 'ASC'
			);
			$query      = new WP_Query( $query_args );

			$html_output = '';

			$html_output .= '<div class="wrap"><div class="product_table">';

			$i = 0;
			while ( $query->have_posts() ) : $query->the_post();
				$html_output .= '';
				$html_output .= '';
				/*
				 * Prepare the content so it uses breaks even though get_the_content() is used
				 */
				$get_the_content = get_the_content();
				$the_content     = wpautop( $get_the_content, true );

				/*
				 * Check if a registration is needed
				 */
				$no_register = get_post_meta( get_the_id(), 'event_register', true );
				if ( $no_register == true ) {
					$register = '';
				} else {
					$register = '<div class="prodov_register"><button type="submit">Jetzt anmelden</button></div>';
				}

				$html_output .= '
				<div class="product">
				<form action="' . get_permalink( $option['registration_page'] ) . '" method="post" name="product_form">
				<input type="hidden" name="kurs" value="' . get_the_ID() . '">
				<article class="prodov_container">

					<section class="prodov_header"><span><b>' . get_the_title() . '</b></span></section>
					<section class="prodov_image">
						' . get_the_post_thumbnail( get_the_id(), 'post-thumbnail', array( 'class' => 'proshow_responsive_image' ) ) . '
						' . $register . '
						<div class="prodov_price"><span>' . get_post_meta( get_the_id(), 'event_price', true ) . '</span></div>
					</section>
					<section>
						<div class="prodov_content" id="prodov-content-' . $i . '"><p>' . $the_content . '</p></div>
					</section>

				</article>
				</form>
				</div>';
				$i ++;
			endwhile;
			wp_reset_postdata();

			$html_output .= '</div></div>';

			return $html_output;
		}


		add_shortcode( 'event_registration', 'register_registration_shortcode' );
		function register_registration_shortcode( $atts, $content = null ) {
			$option      = get_option( 'event_showcase' );
			$html_output = '';

			if ( isset( $_POST['kurs'] ) ):

				$event           = get_post( $_POST['kurs'], 'array_a' );
				$there_are_dates = false;
				$disabled        = 'disabled';

				$query_args = array(
					'post_type'     => 'es_date',
					'post_status'   => 'publish',
					'no_found_rows' => false,
					'order'         => 'ASC',
					'meta_query'    => array(
						array(
							'key'   => 'date_event',
							'value' => $event->ID
						),
						array(
							'key'     => 'date_first',
							'value'   => date( "Y-m-d" ),
							'compare' => '>',
							'type'    => 'DATE'
						)
					)
				);

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) :
					$there_are_dates = true;
					$disabled        = '';
				endif;

				$html_output .= '<h1>Anmeldung für unseren ' . $event->post_title . '</h1>';


				$html_output .= '<form action="' . get_permalink( get_the_ID() ) . '" method="post" name="registration_form">';


				$html_output .= '<input type="hidden" name="registered" value="1">';


				$html_output .= '<table class="registration">';


				$html_output .= '<tr></tr>';


				$html_output .= '<tr>
                                    <th><label for="name">Vor- und Nachname<span>*</span>:</label></th>
                                    <td><input type="text" name="name" id="name" required ' . $disabled . ' /></td>
                                </tr>';


				$html_output .= '<tr>
                                    <th><label for="email">E-Mail<span>*</span>:</label></th>
                                    <td><input type="email" name="email" id="email" required ' . $disabled . ' /></td>
                                </tr>';


				$html_output .= '<tr><td colspan="2" class="registration_description"><p>Wähle die Termine aus, für die du dich bei einem unserer Kurse anmelden möchtest.</p></td></tr>';


				$html_output .= '<tr><th><label>Termin<span>*</span>:</label></th><td>';
				if ( $there_are_dates ) :
					while ( $query->have_posts() ) : $query->the_post();
						$first_date  = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( get_the_ID(), 'date_first', true ) ) );
						$second_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( get_the_ID(), 'date_second', true ) ) );
						$html_output .= '<input type="radio" name="date" id="date_' . get_the_id() . '" value="' . get_the_ID() . '" required/>
										 <label for="date_' . get_the_id() . '"> ' . $first_date . ' und ' . $second_date . '</label><br>';
					endwhile;
				else:
					$html_output .= '<p class="registration_kein_termin">Leider ist noch kein weiterer ' . $event->post_title . ' geplant. 
																		 Um informiert zu werden, wann unser nächster Strickkurs stattfindet, 
																		 kannst du dich <a href="#">hier</a> in unseren Newsletter eintragen.</p>';
				endif;
				wp_reset_postdata();
				$html_output .= '</td></tr>';

				$html_output .= '<tr><td colspan="2" class="registration_description"><p>Wähle bitte die Zahlungsmethode aus, 
                																		 mit der du die Kursgebühr zahlen möchest.</p></td></tr>';


				$html_output .= '<tr>
                                    <th><label for="name">Zahlungsmethode<span>*</span>:</label></th>
                                    <td>
                                        <input ' . $disabled . ' type="radio" name="zahlung" id="zahlung_paypal" value="paypal" required/> 
                                            <label for="zahlung_paypal">Paypal<sup>1</sup></label><br>
                                        <input ' . $disabled . ' type="radio" name="zahlung" id="zahlung_ueberweisung" value="ueberweisung" required/> 
                                            <label for="zahlung_ueberweisung">Überweisung<sup>2</sup></label><br>
                                        <input ' . $disabled . ' type="radio" name="zahlung" id="zahlung_bar" value="bar"/> 
                                            <label for="zahlung_bar" required>Bar<sup>3</sup></label>
                                    </td>
                                 </tr>';


				$html_output .= '<tr><td colspan="2" class="registration_infos">
    
                                    <p><sup>1</sup> Möchtest du mit Paypal zahlen gelangst du nach klicken auf "Anmelden" auf
                                    die Paypal Seite, auf der du dich mit deinen Paypal-Kontodaten einloggen kannst.</p>
    
                                    <p><sup>2</sup> Bei Zahlung per Überweisung erhälst du nach Anmeldung über das obrige
                                    Formular eine E-Mail an die angegebene E-Mail Adresse, die alle Zahlungsinformationen enthält.
                                    Die Anmeldung ist erfolgreich ausgeführt sobald das Geld bei uns eingetroffen ist. Ist dies passiert
                                    erhälst du von uns eine Bestätigung, mit der du an unserem Kurs teilnehmen kannst.</p>
    
                                    <p><sup>3</sup> Wenn du Bar bezahlen möchtest erhälst du direkt im Laden eine Bescheinigung
                                    mit der du an unserem Kurs teilnehmen kannst.</p>
    
                                 </td><tr>';


				$html_output .= '<tr><td colspan="2" class="registration_submit">
                                    <p>Ich habe die <a href="' . get_permalink( $option['tc_page'] ) . '">AGB</a> und 
                                    <a href="' . get_permalink( $option['privacy_page'] ) . '">Datenschutzbestimmungen</a> 
                                    gelesen<br>und akzeptiere diese mit meiner Ameldung</p>
                                    <input ' . $disabled . ' type="submit" value="Anmelden" /></form>
                                    </td></tr>';


				$html_output .= '</table>'; // end table


				$html_output .= '</form>';


			elseif ( isset( $_POST['registered'] ) && $_POST['registered'] == true ) :

				$html_output .= '<h1>Erfolg</h1>';

			else:

				$html_output .= '<h2>Kein Kurs ausgewählt</h2>';

				$html_output .= '<p>Um dich für einen Kurs anzumelden musst du zunächst <a href="' . get_permalink( $option['showcase_page'] ) . '">hier</a> einen auswählen.
                                 Klicke dort auf \'Jetzt anmelden\' und komme dadurch auf diese Seite zurück, wo du dann deine persönlichen Infos eingeben 
                                 und einen Termin auswählen kannst.</p>';

			endif;

			return $html_output;
		}
	}

}
