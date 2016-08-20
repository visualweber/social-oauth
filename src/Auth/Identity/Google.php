<?php
namespace SocialOAuth\Auth\Identity;

use \SocialOAuth\Resource\Google as Resource;

class Google extends Generic
{
   protected $_api;
 
   public function __construct($token)
   {
		$this->_api = new Resource($token);
		$this->_name = 'google';
		$this->_id = $this->_api->getId();
   }
 
	public function getApi()
	{
		return $this->_api;
	}
}
