<?php

class WPF_Mailengine {

	/**
	 * Contains essential params
	 */

	public $params;

	/**
	 * Lets pluggable functions know which features are supported by the CRM
	 */

	public $supports;

	/**
	 * SoapClient
	 */

	public $subscribe_service;

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   2.0
	 */

	public function __construct() {

		$this->slug     = 'mailengine';
		$this->name     = 'Mailengine';
		$this->supports = array('add_tags', 'add_fields');

		// Set up admin options
		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/class-wpf-mailengine-admin.php';
			new WPF_Mailengine_Admin( $this->slug, $this->name, $this );
		}
	}

	/**
	 * Sets up hooks specific to this CRM
	 *
	 * @access public
	 * @return void
	 */

	public function init() {

		// add_filter( 'wpf_format_field_value', array( $this, 'format_field_value' ), 10, 3 );

	}


	/**
	 * Gets params for API calls
	 *
	 * @access  public
	 * @return  array Params
	 */

	public function get_params( $wsdl_url = null, $client_id = null, $subscribe_id = null) {

		// Get saved data from DB
		if ( empty( $wsdl_url ) || empty( $client_id ) || empty( $subscribe_id ) ) {
			$wsdl_url = wp_fusion()->settings->get( 'mailengine_wsdl_url' );
			$client_id = wp_fusion()->settings->get( 'mailengine_client_id' );
			$subscribe_id = wp_fusion()->settings->get( 'mailengine_subscribe_id' );
		}

		
		$this->subscribe_service = new \SoapClient($wsdl_url, [
			'cache_wsdl' => WSDL_CACHE_NONE,
			'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | 9,
			'exceptions' => false
		]);
		
		/*
		catch (\SoapFault $e) {
			
	    	die('soapfault never fires!');
		} catch (\Exception $e) {
		    die('exception won\t t');
		} catch (\ErrorException $e) {
	    	die('error exception also doesn\'t error');
		}
*/
		$this->params = array(
			'wsdl_url' => $wsdl_url,
			'client_id' => $client_id,
			'subscribe_id' => $subscribe_id
		);



		return $this->params;
	}


	/**
	 * Initialize connection
	 *
	 * @access  public
	 * @return  bool
	 */

	public function connect( $wsdl_url = null, $client_id = null, $subscribe_id = null, $test = false ) {

		if ( $test == false ) {
			return true;
		}

		if ( ! $this->params ) {
			$this->get_params( $wsdl_url, $client_id, $subscribe_id );
		}
		
		// Validate the connection with a dummy userdata request

		$result = $this->subscribe_service->GetUserData($this->params['client_id'], $this->params['subscribe_id'], 'id', 0);
		if (is_soap_fault($result)) {
			return new WP_Error( $result->faultcode, "SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})" );		    
		}

		if (is_array($result) || $result == 'invalid user'){
			return true;
		}
	}


	/**
	 * Performs initial sync once connection is configured
	 *
	 * @access public
	 * @return bool
	 */

	public function sync() {

		if ( is_wp_error( $this->connect() ) ) {
			return false;
		}

		$this->sync_tags();
		$this->sync_crm_fields();

		do_action( 'wpf_sync' );

		return true;

	}


	/**
	 * Gets all available tags and saves them to options
	 *
	 * @access public
	 * @return array Lists
	 */

	public function sync_tags() {

		if ( ! $this->params ) {
			$this->get_params();
		}


		$request  = $this->url . '/endpoint/';
		$response = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$available_tags = array();

		// Load available tags into $available_tags like 'tag_id' => 'Tag Label'

		wp_fusion()->settings->set( 'available_tags', $available_tags );

		return $available_tags;
	}


	/**
	 * Loads all custom fields from CRM and merges with local list
	 *
	 * @access public
	 * @return array CRM Fields
	 */

	public function sync_crm_fields() {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request    = $this->url . '/endpoint/';
		$response   = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$crm_fields = array();

		// Load available fields into $crm_fields like 'field_key' => 'Field Label'

		asort( $crm_fields );
		wp_fusion()->settings->set( 'crm_fields', $crm_fields );

		return $crm_fields;
	}


	/**
	 * Gets contact ID for a user based on email address
	 *
	 * @access public
	 * @return int Contact ID
	 */

	public function get_contact_id( $email_address ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request      = $this->url . '/endpoint/';
		$response     = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		// Parse response for contact ID here

		return $contact_id;
	}


	/**
	 * Gets all tags currently applied to the user, also update the list of available tags
	 *
	 * @access public
	 * @return void
	 */

	public function get_tags( $contact_id ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request      = $this->url . '/endpoint/';
		$response     = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		// Parse response to create an array of tag ids. $tags = array(123, 678, 543);

		return $tags;
	}

	/**
	 * Applies tags to a contact
	 *
	 * @access public
	 * @return bool
	 */

	public function apply_tags( $tags, $contact_id ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request 		= $this->url . '/endpoint/';
		$params 		= $this->params;
		$params['body'] = $tags;

		$response = wp_remote_post( $request, $params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		return true;
	}

	/**
	 * Removes tags from a contact
	 *
	 * @access public
	 * @return bool
	 */

	public function remove_tags( $tags, $contact_id ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request 		= $this->url . '/endpoint/';
		$params 		= $this->params;
		$params['body'] = $tags;

		$response = wp_remote_post( $request, $params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		return true;

	}


	/**
	 * Adds a new contact
	 *
	 * @access public
	 * @return int Contact ID
	 */

	public function add_contact( $contact_data, $map_meta_fields = true ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		if ( $map_meta_fields == true ) {
			$contact_data = wp_fusion()->crm_base->map_meta_fields( $contact_data );
		}

		$request 		= $this->url . '/endpoint/';
		$params 		= $this->params;
		$params['body'] = $contact_data;

		$response = wp_remote_post( $request, $params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );

		// Get new contact ID out of response

		return $contact_id;

	}

	/**
	 * Update contact
	 *
	 * @access public
	 * @return bool
	 */

	public function update_contact( $contact_id, $contact_data, $map_meta_fields = true ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		if ( $map_meta_fields == true ) {
			$contact_data = wp_fusion()->crm_base->map_meta_fields( $contact_data );
		}

		$request 		= $this->url . '/endpoint/';
		$params 		= $this->params;
		$params['body'] = $contact_data;

		$response = wp_remote_post( $request, $params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		return true;
	}

	/**
	 * Loads a contact and updates local user meta
	 *
	 * @access public
	 * @return array User meta data that was returned
	 */

	public function load_contact( $contact_id ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request = $this->url . '/endpoint/';
		$response = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$user_meta      = array();
		$contact_fields = wp_fusion()->settings->get( 'contact_fields' );
		$body_json      = json_decode( wp_remote_retrieve_body( $response ), true );

		foreach ( $contact_fields as $field_id => $field_data ) {

			if ( $field_data['active'] == true && isset( $body_json['data'][ $field_data['crm_field'] ] ) ) {
				$user_meta[ $field_id ] = $body_json['data'][ $field_data['crm_field'] ];
			}

		}

		return $user_meta;
	}


	/**
	 * Gets a list of contact IDs based on tag
	 *
	 * @access public
	 * @return array Contact IDs returned
	 */

	public function load_contacts( $tag ) {

		if ( ! $this->params ) {
			$this->get_params();
		}

		$request = $this->url . '/endpoint/';
		$response = wp_remote_get( $request, $this->params );

		if( is_wp_error( $response ) ) {
			return $response;
		}

		$contact_ids = array();

		// Iterate over the contacts returned in the response and build an array such that $contact_ids = array(1,3,5,67,890);


		return $contact_ids;

	}

}