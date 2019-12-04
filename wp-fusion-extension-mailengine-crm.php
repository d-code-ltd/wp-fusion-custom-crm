<?php

/*
Plugin Name: WP Fusion - Mailengine CRM
Description: Mailengine CRM for WP Fusion based on "Boostrap for connecting WP Fusion to a custom CRM"
Plugin URI: https://github.com/d-code-ltd/wp-fusion-extension-mailengine-crm
Version: 1.0
Author: d-code ltd
Author URI: https://www.d-code.hu/
*/

/**
 * @copyright Copyright (c) 2018. All rights reserved.
 *
 * @license   Released under the GPL license http://www.opensource.org/licenses/gpl-license.php
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 *
 */

// deny direct access
if(!function_exists('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}


if ( ! class_exists( 'WPF_Mailengine' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wpf-mailengine.php';
}

/**
 * Add our mailengine CRM class to the list of registered CRMs
 *
 * @return  array CRMs
 */

function wpf_mailengine_crm( $crms ) {	
	$crms['mailengine'] = 'WPF_Mailengine';		
	return $crms;
}

add_filter( 'wpf_crms', 'wpf_mailengine_crm');
