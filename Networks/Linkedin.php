<?php

namespace SocialAuth\Networks;

/**
 * This class is used to encapsulate the calls for each specific social network
 */
class Linkedin extends Base{

	/**
	 *
	 * @var \LinkedIn\LinkedIn
	 */
	protected $linkedinApi;

	/**
	 *
	 * @param array $configs - ['key' => '', 'secret' => '']
	 * @throws \Exception
	 */
	public function __construct(array $configs){
		if(!isset($configs['key']) || !isset($configs['secret']) || !isset($configs['callback'])){
			throw new \Exception('The configuration array does not contain the element(s) "key" and/or "secret"  and/or "callback"');
		}

		$this->callbackUrl = $configs['callback'];

		$this->linkedinApi = new \LinkedIn\LinkedIn([
			'api_key' => $configs['key'],
			'api_secret' => $configs['secret'],
			'callback_url' => $configs['callback']
		]);
	}

	public function getSocialLoginUrl(){
		return $this->linkedinApi->getLoginUrl([\LinkedIn\LinkedIn::SCOPE_BASIC_PROFILE, \LinkedIn\LinkedIn::SCOPE_EMAIL_ADDRESS]);
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \SocialAuth\Networks\Base::login()
	 */
	public function login(){
		if(!isset($_REQUEST['code'])){
			throw new Exception('No code returned on LinkedIn access request',1);
		}
		$token = $this->linkedinApi->getAccessToken($_REQUEST['code']);
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