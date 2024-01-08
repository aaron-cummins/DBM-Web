<?php

class AzureADSimpleProvider {

    private $provider;

    public function __construct() {
        $this->provider = $this->getProvider();
    }

    public function getAccessToken($code) {
        return $this->provider->getAccessToken('authorization_code', [
            'code' =>$code
        ]);
    }

    public function getUserFullname($token) {
        $resourceOwner = $this->provider->getResourceOwner($token);
        return $resourceOwner->getFirstName().' '.$resourceOwner->getLastName();
    }

    public function getEmail($token) {
        $resourceOwner = $this->provider->getResourceOwner($token);
        $emailField = $resourceOwner->claim('email');
        return isset($emailField)? $emailField : $resourceOwner->claim('upn');
    }

    public function getAuthorizationUrl() {
        return $this->provider->getAuthorizationUrl(['state' => $this->getState()]);
    }

    public function getLogoutUrl($redirectUrl) {
        return $this->provider->getLogoutUrl($redirectUrl);
    }

    public function getState() {
        return $this->provider->getState();
    }

    private function getRandomState($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    private function getProvider() {
        $config = [
			'clientId' => AZURE_AD_CLIENT_ID,
			'clientSecret' => AZURE_AD_CLIENT_SECRET,
            'redirectUri' => AZURE_AD_REDIRECT_URI,
            'state' => $this->getRandomState()
			];
		$p = new TheNetworg\OAuth2\Client\Provider\Azure($config);
		$p->scope = ['offline_access User.Read'];
		$p->urlAPI = "https://graph.microsoft.com/v1.0/";
        $p->authWithResource = false;
        return $p;
    }
}

?>