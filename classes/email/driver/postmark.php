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


class Email_Driver_Postmark extends \Email_Driver {

	public function __construct($config) 
	{
		parent::__construct($config);
		\Config::load('postmark');
	}

	/**
	 * Sends the email using the postmark email delivery system
	 * 
	 * @return boolean	True if successful, false if not.
	 */	
	protected function _send()
	{
		if (!function_exists('curl_init'))
		{
			//$this->_debug_message('Could not load curl. Make sure curl is enabled for postmark to work.', 'error');
			return false;
		}
		
		$data['Subject'] = $this->subject;	
		$data['From'] = static::format_addresses(array($this->config['from']));
		$data['To'] = static::format_addresses($this->to);

		if (!empty($this->cc)) 
		{
			$data['Cc'] = static::format_addresses($this->cc);
		}
		
		if (!empty($this->bcc)) 
		{
			$data['Bcc'] = static::format_addresses($this->bcc);
		}
		
		if (!empty($this->reply_to))
		{
			$data['ReplyTo'] = static::format_addresses($this->reply_to);
		}
		
		$data['HtmlBody'] = $this->body;
	
		$data['TextBody'] = $this->alt_body;

		if(count($this->attachments, COUNT_RECURSIVE) > 2) 
		{
			foreach($this->attachments as $attachment)
			{
				$contents = '';
				$basename = '';
				if ($attachment['dynamic'] == true)
				{
					// TODO: Dynamic attachment handling
					$basename = $attachment['filename'];
					$contents = $attachment['contents'];
				}
				else
				{
					// TODO: File attachment handling
					$filename = $attachment['filename'];
					$basename = basename($filename);
					if ( ! file_exists($filename))
					{
						//$this->_debug_message('Could not find the file '.$filename, 'warning');
					}
					else
					{
						$filesize = filesize($filename) + 1;
						if ( ! $fp = fopen($filename, 'r'))
						{
							//$this->_debug_message('Could not read the file '.$filename, 'warning');
						}
						else
						{
							$contents = fread($fp, $filesize);
							fclose($fp);
						}
					}
				}
				if ( ! empty($contents))
				{
					$filename = $attachment['filename'];
					$filetype = is_array($attachment['filetype']) ? $attachment['filetype'][0] : $attachment['filetype'];
					
					$data['Attachments'][] = array(
						'Name'			=> $filename,
						'Content'		=> base64_encode($contents),
						'ContentType'	=> $filetype, 
					);
				}			
			}
		}

		$encoded_data = json_encode($data);
	

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'X-Postmark-Server-Token: ' . \Config::get('postmark_api_key'),
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.postmarkapp.com/email');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$output = curl_exec($ch);

		if (curl_error($ch) != '') {
			show_error(curl_error($ch));
			return false;
		}
		
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		$output = json_decode($output);
		
		if (intval($httpCode / 100) != 2) 
		{
			//$this->_debug_message("Postmark Error - Response: {$output->Message}", 'error');
			return false;
		}
		
		return true;
	}
}
/* End of file packages/postmark/classes/email/postmark.php */
