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
		add_action( 'show_field_mailengine_client_id_end', array( $this, 'show_field_mailengine_client_id_end' ), 10, 2 );

		
		if ($this->check_requirements()){
			// AJAX
			add_action( 'wp_ajax_wpf_test_connection_' . $this->slug, array( $this, 'test_connection' ) );

			if ( wp_fusion()->settings->get( 'crm' ) == $this->slug ) {
				$this->init();
			}
		}
		

		

	}

	/**
	 * Hooks to run when this CRM is selected as active
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function init() {		

	}

	/**
	 * Hooks to run when this CRM is selected as active
	 *
	 * @access  public
	 * @since   1.0
	 */

	public function check_requirements() {
		$eligible = true;
		// Check requirements - SoapClient
		if ( !class_exists('SoapClient') ) {
			$eligible = false;
			add_action( 'admin_notices', array( $this, 'soapclient_missing_notice' ) );
		}

		return $eligible;
	}


	/**
	 * Returns error message and deactivates plugin when error returned.
	 *
	 * @access public
	 * @return mixed error message.
	 */

	public function soapclient_missing_notice() {
		echo '<div class="notice notice-error">';
		echo '<p><strong>Warning:</strong> Mailengine CRM for WP Fusion requires SoapClient for php in order to function properly. This instance does not have SoapClient for php installed.</p>';
		echo '</div>';
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
			'desc'	  => __( 'Before attempting to connect to Mailengine, you\'ll first need to enable Soap access. You can do this by requesting a <strong>client_id</strong> and get the <strong>subscribe_id</strong> from the group configuration screen. The <strong>wsdl url</strong> can be found in the developers guide (<a href="https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks" target="_blank">hu</a> / <a href="https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.2et92p0" target="_blank">en</a>)', 'wp-fusion' )
		);

		$new_settings['mailengine_wsdl_url'] = array(
			'title'   => __( 'URL', 'wp-fusion' ),
			'desc'    => __( 'URL of your Mailengine WSDL', 'wp-fusion' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'setup'
		);

		$new_settings['mailengine_subscribe_id'] = array(
			'title'       => __( 'Subscribe id', 'wp-fusion' ),
			'desc'        => __( 'Enter the Subscribe id for your Mailengine group.', 'wp-fusion' ),			
			'type'    => 'text',
			'section'     => 'setup',
			'class'       => 'api_key'			
		);

		$new_settings['mailengine_client_id'] = array(
			'title'   => __( 'Client id', 'wp-fusion' ),
			'desc'    => __( 'Enter the Client id for your Mailengine account.', 'wp-fusion' ),
			'std'     => '',			
			'type'        => 'api_validate',
			'class'   => 'api_key',
			'password'	  => true,
			'section' => 'setup',
			'post_fields' => array( 'mailengine_wsdl_url', 'mailengine_client_id', 'mailengine_subscribe_id')
		);

		$new_settings['mailengine_developers_guide'] = array(
			'title'   => __( 'Developers guide', 'wp-fusion' ),
			'std'     => 0,
			'type'    => 'heading',
			'section' => 'setup',
			'desc'	  => __( '<ul><li><a href="https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks" target="_blank">Hungarian</a></li><li><a href="https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.2et92p0" target="_blank">English</a></li></ul>', 'wp-fusion' )
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

	public function show_field_mailengine_client_id_end( $id, $field ) {

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