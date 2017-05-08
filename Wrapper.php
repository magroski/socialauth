<?php

namespace SocialAuth;

use SocialAuth\Networks\Facebook;
use SocialAuth\Networks\Google;
use SocialAuth\Networks\Linkedin;

/**
 * This class is used to encapsulate the calls for each specific social network 
 */
class Wrapper{

	/**
	 * 
	 * @var SocialAuth\Networks\Base
	 */
	protected $socialObject;
	
	/**
	 * 
	 * @param string $socialNetworkName - 'facebook' || 'google' || 'linkedin']
	 * @param array $configs - ['key' => '', 'secret' => '']
	 * @throws \Exception
	 */
	public function __construct(string $socialNetworkName, array $configs){
		if(!isset($configs['key']) || !isset($configs['secret'])){
			throw new \Exception('The configuration array does not contain the element(s) "key" and/or "secret"');
		}
		
		switch($socialNetworkName){
			case 'facebook':
				$this->socialObject = new Facebook($configs);
				break;
			case 'google':
				$this->socialObject = new Google($configs);
				break;
			case 'linkedin':
				$this->socialObject = new Linkedin($configs);
				break;
			default:
				throw new \Exception(sprintf('Social network "%s" is not supported',$socialNetworkName));
		}
	}
	
	/**
	 * 
	 * @param array $params - The $_REQUEST array so the social object can get the values return by the social network.
	 */
	public function login(){
		return $this->socialObject->login();
	}
	
	public function getSocialLoginUrl(string $redirectUrl){
		return $this->socialObject->getSocialLoginUrl($redirectUrl);
	}
	
	public function getProfile(){
		return $this->socialObject->getProfile();
	}
	
}