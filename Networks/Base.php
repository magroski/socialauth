<?php

namespace SocialAuth\Networks;

abstract class Base{
	
	protected $callbackUrl;
	protected $accessToken;
	
	public abstract function __construct(array $configs);
	
	public abstract function login();
	
	public abstract function getSocialLoginUrl();
	
	public abstract function getProfile();
	
}