# Postmark

Postmark extends Fuel's email class via a driver and allows you to send emails via the transactional email service. (http://postmarkapp.com)

# Install

to install this package simply add this source to your package configuration file:

	http://github.com/tomschlick

and then use this command:

	php oil package install postmark

# Usage

You can use this exactly the same way as you would use the default email protocols, just set your postmark api key in /fuel/packages/postmark/config/postmark.php

```php
Email::factory('default', array('driver' => 'postmark'))
	->to('to@yoursite.com')
	->from('from@yoursite.com')
	->subject('testing123')
	->html_body('Your message goes here.')
	->send();
```
