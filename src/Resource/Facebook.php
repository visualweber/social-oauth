<?php
namespace SocialOAuth\Resource;

use \SocialOAuth\OAuth2\Consumer as Consumer;
use \Zend_Registry as Registry;
class Facebook
{
    protected $_accessToken;

    protected $data = array();

    public function __construct($accessToken)
    {
        $this->_accessToken = $accessToken;
    }

    public function getId()
    {
        $endpoint = 'https://graph.facebook.com/me?fields=id';
        $id = json_decode($this->_getData('id', $endpoint));
        if(isset($id->error)) {
            return NULL;
        }
        return $id->id;
    }

    public function getProfile()
    {
        $config = Registry::get('config');
        $option = $config['facebook'];
        
        $endpoint = 'https://graph.facebook.com/me';
        if(isset($option['fields'])):
            $endpoint.='?fields='.$option['fields'];
        endif;
        return (array) json_decode($this->_getData('profile', $endpoint));
    }

    public function getFriends()
    {
        $endpoint = 'https://graph.facebook.com/me/friends';
        return json_decode($this->_getData('friends', $endpoint))->data;
    }

    public function getPicture($large = false)
    {
        if (!$large) {
            $endpoint = 'https://graph.facebook.com/me/picture';
            return $this->_getData('picture', $endpoint, false);
        } else {
            $endpoint = 'https://graph.facebook.com/me/picture?type=large';
            return $this->_getData('picture_big', $endpoint, false);
        }
    }

    protected function _getData($label, $url, $redirects = true)
    {
        if (!$this->_hasData($label)) {
            $value = Consumer::getData($url, 
                                       $this->_accessToken['access_token'],
                                       $redirects);
            $this->_setData($label, $value);
        }
        return $this->data[$label];
    }

    protected function _setData($label, $value)
    {
        $this->data[$label] = $value;
    }

    protected function _hasData($label)
    {
        return isset($this->data[$label]) && (NULL !== $this->data[$label]);
    }
}
