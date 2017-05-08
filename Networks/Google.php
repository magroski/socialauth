<?php

namespace SocialAuth\Networks;

/**
 * This class is used to encapsulate the calls for each specific social network 
 */
class Google extends Base{

	/**
	 *
	 * @var \Google_Client
	 */
	protected $googleApi;
	
	/**
	 *
	 * @param array $configs - ['key' => '', 'secret' => '', 'callback' => 'CALLBACK URL']
	 * @throws \Exception
	 */
	public function __construct(array $configs){
		if(!isset($configs['key']) || !isset($configs['secret'])){
			throw new \Exception('The configuration array does not contain the element(s) "key" and/or "secret"');
		}
		
		$this->callbackUrl = $configs['callback'];
		
		$this->googleApi = new \Google_Client([
			'client_id'		=> $configs['key'],
			'client_secret'	=> $configs['secret'],
		]);
	}
	
	public function getSocialLoginUrl(){
		$this->googleApi->setRedirectUri($this->callbackUrl);
		return $this->googleApi->createAuthUrl();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \SocialAuth\Networks\Base::login()
	 */
	public function login(){
		$token = $this->googleApi->fetchAccessTokenWithAuthCode($_GET['code']);
		$this->googleApi->setAccessToken($token);
	}
	
	public function getProfile(){
		$oauth2 = new \Google_Service_Oauth2($this->googleApi);
		$plus = new \Google_Service_Plus($this->googleApi);

		$me = $plus->people->get('me');
			
		if(!isset($me['id'])) throw new \Exception('User does not exist');
		
		$data = [
			'social_id'	=>$me['id'],
			'full_name'	=>$me['displayName']
		];
	
		if(isset($me['image']) && $me['image']['url'] != '') {
			$data['picture'] = str_replace('sz=50','sz=200', $me['image']['url']);
		}
	
		$user = $oauth2->userinfo->get();
		if(isset($user['email'])) $data['email'] = $user['email'];

		return $data;
	}
	
}