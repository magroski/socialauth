<?php

namespace SocialAuth\Networks;

abstract class Base{

	public abstract function __construct(array $configs);
	
	public abstract function login();
	
	public abstract function getSocialLoginUrl(string $redirectUrl);
	
	public abstract function getProfile();
	
}