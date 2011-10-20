<?php

/**
 * Postmark Email Delivery library for Fuel
 *
 * @package		Postmark
 * @version		1.1
 * @author		Tom Schlick (tom@tomschlick.com)
 * @link		http://github.com/tomschlick/fuel-postmark
 * 
 */


Autoloader::add_classes(array(
	'Email_Driver_Postmark' => __DIR__.'/classes/email/driver/postmark.php'
));
