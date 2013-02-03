<?php
/**
 * GravatarRPC
 * @package Gravatar
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Wouter van Vliet / Interpotential <wouter@interpotential.com>
 * @see http://www.interpotential.com
 * @see http://www.wordpress.com
 * @version 1.0 (revision $Rev: 435 $)
 * @copyright Copyright (c) 2009, Interpotential
 * @see http://en.gravatar.com/site/implement/xmlrpc
 */

/**
 * Wrapper class for the Gravatar XMLRPC api
 * Encapulates all methods from the api
 * Usage: 
 * <code>
 * <?php
 * $api = new GravatarRPC(1234, 'email@example.com');
 * $api->test() // test the api
 * $hasGravatar = $api->exists('wouter@interpotential.com');
 * // and so on
 * ?>
 * </code>
 * It requires the XML_RPC class from PEAR, which can be found on pear.php.net:
 * http://pear.php.net/package/XML_RPC
 * though usually just by calling: sudo pear install XML_RPC, or using a PEAR
 * webinstaller.
 *
 * @package Gravatar
 */
class GravatarRPC {

	protected $client;
	protected $email;
	protected $apikey;

	/**
	 * Constructor
	 * @param string The apikey belonging to the account we're 
	 *  	  working with. You can get your apikey on the edit profile page
	 *		  on wordpress.com
	 * @param string The email address the apikey matches with
	 */
	public function __construct($apikey, $email) {
		require_once 'XML/RPC.php';

		$this->apikey = $apikey;
		$this->email = strtolower(trim($email));
	}

	/**
	 * Construct a client object
	 */
	protected function client() {

		if ($this->client === null) {
			$this->client = new XML_RPC_Client('/xmlrpc?user='.md5($this->email), 'secure.gravatar.com', 443);
		}

		return $this->client;
	}
	/**
	 * Call a method
	 * @param string Method name, automatically gets "grav." prepended to it
	 * @param Array List of named arguments
	 */
	protected function call($method, $params = Array()) {

		#$params = array_map('XML_RPC_encode', $params);
		$params = array_merge($params, Array(
			'apikey'	=> $this->apikey,
		));
		$msg = new XML_RPC_Message("grav.$method", Array(XML_RPC_encode($params)));
		$response = $this->client()->send($msg);
		if (!$response->faultCode()) {
			$value = $response->value();
			$value = XML_RPC_decode($value);
			return $value;
		} else {
			throw new GravatarException($response->faultString(), $response->faultCode());
		}
	}

	/**
	 * Check whether a hash has a gravatar
	 * @param Array List of email addresses to check
	 */
	public function exists($emails = Array()) {

		if (!is_array($emails)) {
			$emails = Array($emails);
		}

		$emails = array_map(function($mail) {
			return strtolower(md5(strtolower(trim($mail))));
		}, $emails);

		$params = Array($emails);

		return $this->call('exists', Array('hashes' => $emails));
	}

	/**
	 * Return an array of userimages for this account 
	 */
	public function userimages() {
		$resp = $this->call('userimages');
		foreach($resp as $hash => $info) {
			$resp[$hash] = (object)Array(
				'rating'	=> $info[0],
				'url'		=> $info[1],
			);
		}
		return $resp;
	}
	/**
	 * Save binary image data as a userimage for this account
	 * @param string A data blob or filename to submit as Gravatar
	 * @param int Rating of this image, 0 = g, 1 = pg, 3 = x
	 * @param boolean Treat $data as string path to filename, slurp this
	 *   			 and send it to Gravatar
	 * @returns mixed boolean(false) on failure, image string on success
	 */
	public function saveData($data, $rating, $slurp = false) {

		$rating = (int)$rating;
		if ($rating < 0 || $rating > 3) throw new InvalidArgumentException('Rating should be a number from 0 to 3');

		if ($slurp == true) {

			if (!is_file($data) || !is_readable($data)) {
				throw new InvalidArgumentException('Path provided is not a readable file.');
			} 
			$data = file_get_contents($data);
		}

		return $this->call('saveData', Array(
			'rating' => $rating,
			'data'	=> base64_encode($data),
		));
	}
	/**
	 * Read an image via its URL and save that as a userimage for this account 
	 * @param string A full url to an image on the internet
	 * @param int Rating of this image, 0 = g, 1 = pg, 2 = r, 3 = x
	 * @returns mixed boolean(false) on failure, image string on success
	 */
	public function saveUrl($url, $rating) {

		$rating = (int)$rating;
		$url = (string)$url;
		if ($rating < 0 || $rating > 3) throw new InvalidArgumentException('Rating should be a number from 0 to 3');

		return $this->call('saveUrl', Array(
			'rating' => $rating,
			'url'	=> $url,
		));
	}
	/**
	 * Use a userimage as a gravatar for one of more addresses on this account
	 * @param string The userimage you wish to use
	 * @param string|array Single email address as string, or list of
	 * 		  email addresses to use this image for
	 * @returns Array List of statusses per email address or single boolean if
	 *  		email was supplied as single string
	 */
	public function useUserimage($image, $email) {

		$returnSingle = false;
		$image = (string)$image;
		if (is_string($email)) {
			$returnSingle = strtolower(trim($email));
			$email = Array($email);	
		}
		if (!is_array($email)) {
			throw new InvalidArgumentException('Email address is not a string or array of email addresses');	
		}

		// give all email addresses in lowercase
		$email = array_map(function($e) {
			return strtolower(trim($e));
		}, $email);

		$value = $this->call('useUserimage', Array(
			'userimage'	=> $image,
			'addresses'	=> $email,
		));

		// if we came with a string, we only want one return value
		if ($returnSingle && isset($value[$returnSingle])) {
			return $value[$returnSingle];
		}

		return $value;

	}
	/**
	 * Remove the userimage associated with one or more email addresses
	 * @param string|array Either a string containing one email address, or an
	 *					   array containing multiple email addresses for which
	 *					   you no longer want to use a gravatar
	 * @returns Array List of statusses per email address or single boolean if
	 *  		email was supplied as single string
	 */
	public function removeImage($email) {

		$returnSingle = false;
		if (is_string($email)) {
			$returnSingle = strtolower(trim($email));
			$email = Array($email);	
		}
		if (!is_array($email)) {
			throw new InvalidArgumentException('Email address is not a string or array of email addresses');	
		}

		// give all email addresses in lowercase
		$email = array_map(function($e) {
			return strtolower(trim($e));
		}, $email);

		$value = $this->call('removeImage', Array(
			'addresses'	=> $email,
		));

		// if we came with a string, we only want one return value
		if ($returnSingle && isset($value[$returnSingle])) {
			return $value[$returnSingle];
		}

		return $value;

	}
	/**
	 * Remove a userimage from the account and any email addresses with which 
	 * it is associated
	 * @param The userimage you wish to delete
	 * @return boolean status
	 */
	public function deleteUserimage($image) {
		$value = $this->call('deleteUserimage', Array(
			'userimage'	=> $image,
		));

		return $value;
	}

	/**
	 * Get a list of addresses for this account
	 */
	public function addresses() {
		$resp = array_map(function($info) {
			return (object)$info;
		}, $this->call('addresses'));

		return $resp;
	}
	/**
	 * Test the api
	 */
	public function test() {
		return $this->call('test');
	}
}
/**
 * @package Gravatar
 */
class GravatarException extends Exception { }
