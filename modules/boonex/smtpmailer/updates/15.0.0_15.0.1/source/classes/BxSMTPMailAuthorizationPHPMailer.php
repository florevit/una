<?php

use PHPMailer\PHPMailer\OAuthTokenProvider;

/**
 * Mail Authorisation via oAuth2 for PHPMailer
 */
class BxSMTPMailAuthorizationPHPMailer extends BxSMTPMailAuthorization implements OAuthTokenProvider {


	/**
	 * Get singleton class instance
	 *
	 * Config for client secret login [clientId, clientSecret, tenantId, tenantName]
	 * Config for certificate login [clientId, tenantId, tenantName, x509Cert, x509Key]
	 *
	 * @param object|array|null $config Configuration values
	 * @return self
	 */
	public static function getInstance(object|array|null $config): self{
		if(!self::$instance){
			self::$instance = new self();
		}

		return $config ? self::$instance->setConfig($config) : self::$instance;
	}

	/**
	 * Generate a base64-encoded OAuth token ensuring that the access token has not expired.
	 * The string to be base 64 encoded should be in the form:
	 * "user=<user_email_address>\001auth=Bearer <access_token>\001\001"
	 *
	 * @return string
	 */
	public function getOauth64(): string{

		$accessToken = $this->getAccessToken();

		if($this->hasErrors()){
            $a = $this->getErrors();
            foreach ($a as $s) {
                bx_log('bx_smtp_mailer_oauth', $s, BX_LOG_ERR);
            }
			return '';
		}

		return base64_encode(
			sprintf(
				"user=%s\001auth=Bearer %s\001\001",
				$this->mailAddress,
				$accessToken
			)
		);
	}

	/**
	 * Load the access token and its expiration from custom caching storage.
	 * This method should be overridden to meet the project- or framework-specific requirements and possibilities.
	 * @param string $clientId
	 * @return array|null $tokenData on success, null on failure
	 */
	protected function getCache(string $clientId): array|null {

        $oCache = $this->getCacheObject();
        if ($oCache)
            return $oCache->getData($this->getCacheKey($clientId));

		return null;
	}

	/**
	 * Save the access token and its expiration to the custom caching storage.
	 * This method should be overridden to meet the project- or framework-specific requirements and possibilities.
	 * @param string $clientId
	 * @param array $tokenData
	 * @return $this
	 */
	protected function setCache(string $clientId, array $tokenData): self{

        $oCache = $this->getCacheObject();
        if ($oCache)
            $oCache->setData($this->getCacheKey($clientId, $tokenData), $tokenData);

		return $this;
	}

    protected function getCacheObject(): object{

        $sEngine = BxDolDb::getInstance()->getParam('sys_db_cache_engine');
        $oCache = bx_instance('BxDolCache'.$sEngine);
        if ($oCache->isAvailable())
            return $oCache;

        return null;
    }


    protected function getCacheKey(string $clientId): string{

        return 'bx_smtp_oauth_token_' . $clientId . bx_site_hash() . '.php';
    }

}
