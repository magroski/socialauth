<?php

namespace SocialAuth\Networks;

/**
 * This class is used to encapsulate the calls for each specific social network 
 */
class Facebook extends Base{

	/**
	 * 
	 * @var \Facebook\Facebook
	 */
	protected $facebookApi;
	
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
		
		$this->facebookApi = new \Facebook\Facebook([
		  'app_id' => $configs['key'],
		  'app_secret' => $configs['secret'],
		  'default_graph_version' => 'v2.5',
		]);
	}
	
	public function getSocialLoginUrl(){
		$helper = $this->facebookApi->getRedirectLoginHelper();
		$permissions = ['email'];
		return $helper->getLoginUrl($this->callbackUrl, $permissions);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \SocialAuth\Networks\Base::login()
	 * @throws \Facebook\Exceptions\FacebookResponseException
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function login(){
		$helper = $this->facebookApi->getRedirectLoginHelper();
		$this->accessToken = $helper->getAccessToken();
		$this->facebookApi->setDefaultAccessToken($this->accessToken);
	}
	
	public function getProfile(){
		$me = $this->facebookApi->get('/me?fields=id,name,email')->getGraphUser();
		
		return [
			'social_id'	=>$me->getId(),
			'full_name'	=>$me->getName(),
			'email'		=>$me->getField('email'),
			'picture'	=>'https://graph.facebook.com/'.$me->getId().'/picture?type=large'
		];
	}
	
}