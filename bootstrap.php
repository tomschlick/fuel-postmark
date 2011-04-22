<?php

/**
 * Postmark Email Delivery library for Fuel
 *
 * @package		Postmark
 * @version		1.0
 * @author		Tom Schlick (tom@tomschlick.com)
 * @link		http://github.com/tomschlick/fuel-postmark
 * 
 */

Autoloader::add_core_namespace('Postmark');

Autoloader::add_classes(array(
	'Postmark\Email_Postmark' => __DIR__.'/classes/email/postmark.php'
));