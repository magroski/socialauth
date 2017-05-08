<?php

namespace SocialAuth\Networks;

use LinkedIn\LinkedIn;

/**
 * This class is used to encapsulate the calls for each specific social network 
 */
class Linkedin extends Base{

	/**
	 *
	 * @var LinkedIn
	 */
	protected $linkedinApi;
	
	/**
	 *
	 * @param array $configs - ['key' => '', 'secret' => '']
	 * @throws \Exception
	 */
	public function __construct(array $configs){
		if(!isset($configs['key']) || !isset($configs['secret'])){
			throw new \Exception('The configuration array does not contain the element(s) "key" and/or "secret"');
		}
		
		$this->callbackUrl = $configs['callback'];
		
		$this->linkedinApi = new LinkedIn([
			'api_key' => $configs['key'],
			'api_secret' => $configs['secret'],
			'callback_url' => $configs['callback']
		]);
	}
	
	public function getSocialLoginUrl(){
		return $this->linkedinApi->getLoginUrl([LinkedIn::SCOPE_BASIC_PROFILE, LinkedIn::SCOPE_EMAIL_ADDRESS]);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \SocialAuth\Networks\Base::login()
	 */
	public function login(){
		$token = $li->getAccessToken($_REQUEST['code']);
		$this->linkedinApi->setAccessToken($token);
	}
	
	public function getProfile(){
		$user = $this->linkedinApi->get('/people/~:(id,first-name,last-name,picture-url,public-profile-url,email-address)');

		$data = ['social_id'=>$user['id']];
		
		if(isset($user['firstName'])){
			$data['full_name'] = $user['firstName'];
			if(isset($user['lastName'])){
				$data['full_name'] .= ' '.$user['lastName'];
			}
		}
		
		if(isset($user['emailAddress'])) $data['email'] = $user['emailAddress'];
		if(isset($user['pictureUrl']))	 $data['picture']= $user['pictureUrl'];
		
		return $data;
	}
	
}