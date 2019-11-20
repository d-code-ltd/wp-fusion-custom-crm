<?php

class WPF_Mailengine_Admin {

	private $slug;
	private $name;
	private $crm;

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function __construct( $slug, $name, $crm ) {

		$this->slug = $slug;
		$this->name = $name;
		$this->crm  = $crm;

		add_filter( 'wpf_configure_settings', array( $this, 'register_connection_settings' ), 15, 2 );
		add_action( 'show_field_mailengine_header_begin', array( $this, 'show_field_mailengine_header_begin' ), 10, 2 );
		add_action( 'show_field_mailengine_key_end', array( $this, 'show_field_mailengine_key_end' ), 10, 2 );

		// AJAX
		add_action( 'wp_ajax_wpf_test_connection_' . $this->slug, array( $this, 'test_connection' ) );

		if ( wp_fusion()->settings->get( 'crm' ) == $this->slug ) {
			$this->init();
		}

	}

	/**
	 * Hooks to run when this CRM is selected as active
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function init() {

		// Hooks in init() will run on the admin screen when this CRM is active

	}


	/**
	 * Loads CRM connection information on settings page
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function register_connection_settings( $settings, $options ) {

		$new_settings = array();

		$new_settings['mailengine_header'] = array(
			'title'   => __( 'Mailengine Configuration', 'wp-fusion' ),
			'std'     => 0,
			'type'    => 'heading',
			'section' => 'setup',
			'desc'	  => __( 'Before attempting to connect to Mautic, you\'ll first need to enable API access. You can do this by going to the configuration screen, and selecting API Settings. Turn both <strong>API Enabled</strong> and <strong>Enable Basic HTTP Auth</strong> to On.', 'wp-fusion' )
		);

		$new_settings['mailengine_wsdl_url'] = array(
			'title'   => __( 'URL', 'wp-fusion' ),
			'desc'    => __( 'URL of your Mailengine WSDL', 'wp-fusion' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'setup'
		);

		$new_settings['mailengine_client_id'] = array(
			'title'   => __( 'Client id', 'wp-fusion' ),
			'desc'    => __( 'Enter the Client id for your Mailengine account.', 'wp-fusion' ),
			'std'     => '',
			'type'    => 'text',
			'class'   => 'api_key',
			'section' => 'setup'
		);

		$new_settings['mailengine_subscribe_id'] = array(
			'title'       => __( 'Subscribe id', 'wp-fusion' ),
			'desc'        => __( 'Enter the Subscribe id for your Mailengine group.', 'wp-fusion' ),
			//'type'        => 'api_validate',
			'type'    => 'text',
			'section'     => 'setup',
			'class'       => 'api_key',
			'post_fields' => array( 'mailengine_wsdl_url', 'mailengine_client_id', 'mailengine_subscribe_id')
		);

		$settings = wp_fusion()->settings->insert_setting_after( 'crm', $settings, $new_settings );

		return $settings;

	}


	/**
	 * Puts a div around the CRM configuration section so it can be toggled
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function show_field_mailengine_header_begin( $id, $field ) {

		echo '</table>';
		$crm = wp_fusion()->settings->get( 'crm' );
		echo '<div id="' . $this->slug . '" class="crm-config ' . ( $crm == false || $crm != $this->slug ? 'hidden' : 'crm-active' ) . '" data-name="' . $this->name . '" data-crm="' . $this->slug . '">';

	}

	/**
	 * Close out settings section
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function show_field_mailengine_key_end( $id, $field ) {

		if ( $field['desc'] != '' ) {
			echo '<span class="description">' . $field['desc'] . '</span>';
		}
		echo '</td>';
		echo '</tr>';

		echo '</table><div id="connection-output"></div>';
		echo '</div>'; // close #custom div
		echo '<table class="form-table">';

	}

	/**
	 * Verify connection credentials
	 *
	 * @access public
	 * @return bool
	 */

	public function test_connection() {

		$wsdl_url = $_POST['mailengine_wsdl_url'];
		$client_id = $_POST['mailengine_client_id'];
		$subscribe_id = $_POST['mailengine_subscribe_id'];

		$connection = $this->crm->connect( $wsdl_url, $client_id, $subscribe_id, true );

		if ( is_wp_error( $connection ) ) {			
			wp_send_json_error( $connection->get_error_message() );
		} else {

			$options 							= wp_fusion()->settings->get_all();
			$options['mailengine_wsdl_url'] 	= $wsdl_url;
			$options['mailengine_client_id'] 	= $client_id;
			$options['mailengine_subscribe_id'] = $subscribe_id;
			$options['crm'] 					= $this->slug;
			$options['connection_configured'] 	= true;

			wp_fusion()->settings->set_all( $options );

			wp_send_json_success();
		}

		die();

	}


}