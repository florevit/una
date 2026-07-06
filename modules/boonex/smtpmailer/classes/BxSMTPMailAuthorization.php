<?php
/**
 * Retrieve an access token from Microsoft login to authenticate
 *
 *
 * Usage guide:
 * - create a configuration array or object with all required parameters (see below).
 * - create a singleton instance of the class, provide your config and request the access token.
 *
 * An example
 * $accessToken = BxSMTPMailAuthorization::getInstance()->setConfig($config)->getAccessToken();
 * Or as a shorthand
 * $accessToken = BxSMTPMailAuthorization::getInstance($config)->getAccessToken();
 *
 *
 * Configuration parameters and their values.
 * You must specify one of the following configurations.
 * If you have specified both, the class will give preference to the certificate flow over the client secret!
 *
 * Client ID + Secret authentication flow:
 * $config = [
 * 	 'mailAddress' => '',
 *   'clientId' => '',
 *   'clientSecret' => '',
 *   'tenantId' => '',
 *   'tenantName' => '',
 * ];
 *
 * Client ID + certificate authentication flow:
 * $config = [
 * 	  'mailAddress' => '',
 *    'clientId' => '',
 *    'tenantId' => '',
 *    'tenantName' => '',
 *    'x509Cert' => '',
 *    'x509Key' => '',
 *  ];
 *
 * @var string $mailAddress is the senders mail (not the username) of the Outlook user account.
 * @var string $clientId is a UIDv4 hash representing the Outlook user account.
 * @var string $cleintSecret is a ~ 320 bit random hash with special chars.
 * @var string $tenantId is a UIDv4 hash representing the company account.
 * @var string $tenantName is a primary domain name for the company at Microsoft Outlook.
 * @var string $x509Cert is the absolute file path of the public x509 certificate.
 * @var string $x509Key is the absolute file path of the x509 private key.
 *
 * If you need to create a new certificate
 * - open your terminal
 * - navigate to the folder where the files should be stored
 * - execute the following command and provide the requested information
 * - please stick to the naming convention for a better association with the affected mail account
 * - e.g. x509-max.muster@example.com.com-till-05.11.2026.crt
 * $ openssl req -newkey rsa:4096 -new -x509 -days 365 -nodes -out x509-<mail-address>-till-<date>.crt -keyout x509-<mail-address>-till-<date>.key
 *
 * If you want to decode certificates on your own computer, run this OpenSSL command
 * $ openssl x509 -in x509-<mail-address>-till-<date>.crt -text -noout
 *
 */

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Microsoft Announcement
 * Exchange Online to retire Basic auth for Client Submission (SMTP AUTH)
 * https://techcommunity.microsoft.com/blog/exchange/exchange-online-to-retire-basic-auth-for-client-submission-smtp-auth/4114750
 *
 * General Documentation with focus on Web-Apps
 * https://learn.microsoft.com/de-de/exchange/client-developer/legacy-protocols/how-to-authenticate-an-imap-pop-smtp-application-by-using-oauth
 *
 * Documentation MSAL library for server side applications
 * https://learn.microsoft.com/de-de/entra/identity-platform/scenario-daemon-app-registration
 *
 * Documentation for Deamon Apps (serverside logics)
 * https://learn.microsoft.com/de-de/entra/identity-platform/scenario-daemon-app-configuration
 */
class BxSMTPMailAuthorization {

	protected static self|null $instance = null;
	/**
	 * Run time cache for access token buffering
	 * @var array
	 */
	protected array $runtimeCache = [];
	/**
	 * Email Address (the sending mail address not the username)
	 * @var string
	 */
	protected string $mailAddress = '';
	/**
	 * Microsoft client ID
	 * @var string UIDv4 Hash value
	 */
	protected string $clientId = '';
	/**
	 * Microsoft application/client secret
	 * @var string 320-bit random hash with special chars
	 */
	private string $clientSecret = '';
	/**
	 * Microsoft tenant ID / customer number
	 * @var string UIDv4 Hash value
	 */
	private string $tenantId = '';
	/**
	 * Microsoft tenant name / customer subdomain
	 * @var string
	 */
	private string $tenantName = '';
	/**
	 * Absolute path to the x509 public certificate, file extension does not matter (.cer, .crt, .pem etc.)
	 * @var string
	 */
	private string $x509Cert = '';
	/**
	 * Absolute path to the x509 protected key, file extension does not matter (.key, .cer, crt, etc.)
	 * @var string
	 */
	private string $x509Key = '';
	/**
	 * Base64 encoded fingerprint of the public x509 certificate
	 * @var string
	 */
	private string $x509Fingerprint = '';
	/**
	 * URL of the Microsoft authentication API
	 * @var string
	 */
	private string $authenticationUrl = 'https://login.microsoftonline.com/%s/oauth2/v2.0/token';
	/**
	 * Status value: are the parameters for client secret authentication complete?
	 * @var bool
	 */
	private bool $clientSecretParams = false;
	/**
	 * Status value: are the parameters for certificate authentication complete?
	 * @var bool
	 */
	private bool $certificateParams = false;
	/**
	 * List of collected errors
	 * @var array
	 */
	private array $errors = [];





	/**
	 * Get singleton class instance
	 *
	 * Config for client secret login [clientId, clientSecret, tenantId, tenantName]
	 * Config for certificate login [clientId, tenantId, tenantName, x509Cert, x509Key]
	 *  Extended config for certificate login [clientId, tenantId, tenantName, certificates]
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
	 * Set configuration values and reset error collection.
	 *
	 * Config for client secret login [clientId, clientSecret, tenantId, tenantName]
	 * Config for certificate login [clientId, tenantId, tenantName, x509Cert, x509Key]
	 * Extended config for certificate login [clientId, tenantId, tenantName, certificates]
	 *
	 * @param object|array $config
	 * @return $this
	 */
	public function setConfig(object|array $config): self{

		$config = (object)$config;

		$this->mailAddress = $config->mailAddress ?? '';
		$this->clientId = $config->clientId ?? '';
		$this->clientSecret = $config->clientSecret ?? '';
		$this->tenantId = $config->tenantId ?? '';
		$this->tenantName = $config->tenantName ?? '';

		if(!empty($config->certificates)){
			$now = date('Y-m-d');
			// convert to array of objects if array of arrays is given
			$config->certificates = json_decode(json_encode($config->certificates));
			usort($config->certificates, fn($a, $b) => strcmp($b->validFrom ?? '', $a->validFrom ?? ''));
			$certificates = array_filter($config->certificates, function($v) use ($now){
				return ($v->active ?? false) && ($v->validFrom ?? '') <= $now && ($v->validTill ?? '') >= $now;
			}, 0);
			if(!empty($certificates[0])){
				$this->x509Cert = $certificates[0]->x509Cert ?? '';
				$this->x509Key = $certificates[0]->x509Key ?? '';
			}
		}else{
			$this->x509Cert = $config->x509Cert ?? '';
			$this->x509Key = $config->x509Key ?? '';
		}

		$this->errors = [];

		$this->checkAuthenticationScopes();

		return $this;
	}

	/**
	 * Retrieve the current valid access token.
	 * Serve from cache if available and valid, or generate a new one.
	 * @return string|null
	 */
	public function getAccessToken(): string|null{

		$tokenData = $this->getTokenData($this->mailAddress) ?: $this->retrieveAccessToken__certificate() ?: $this->retrieveAccessToken__clientSecret();

		return $tokenData['accessToken'] ?? null;
	}

	/**
	 * Check if the authentication has any errors.
	 * @return bool
	 */
	public function hasErrors(): bool{
		return count($this->errors) > 0;
	}

	/**
	 * Retrieve a list of all collected errors.
	 * @return array
	 */
	public function getErrors(): array{
		return $this->errors;
	}

	/**
	 * Get the name of the certificate for creation routine.
	 * Can also be used to get the file name if you want to check if it already exists.
	 * @param string $fileExt file extension (crt, key)
	 *  @param int $lifetime in days
	 * @return string
	 */
	public function getNewCertFilename(string $fileExt, int $lifetime = 365): string {
		$deadline = strtotime('+'.$lifetime.' days');
		return sprintf('x509-%s-till-%s.%s', $this->mailAddress, strtolower(date('d.m.Y', $deadline)), $fileExt);
	}

	/**
	 * Create a new certificate for an email account.
	 * If a certificate crt and key files with the same name already exist, they will NOT be overwritten!!
	 * @param string|null $absDir
	 * @return array[]
	 * @throws Exception
	 */
	public function createCertificate(string $absDir = null): array {

		$dir = $absDir ?? dirname($this->x509Cert);

		if(!is_dir($dir)){
			throw new Exception("Directory '{$dir}' does not exist");
		}

		$certFileName = $this->getNewCertFilename('crt');
		$keyFileName = $this->getNewCertFilename('key');

		if(file_exists($certFileName) && file_exists($keyFileName)){
			goto response;
		}

		$distinguished_names = [
			'countryName'            => 'DE',
			'stateOrProvinceName'    => 'Bavaria',
			'localityName'           => 'Munich',
			'organizationName'       => 'Org',
			'organizationalUnitName' => $this->mailAddress,
			'commonName'             => $this->mailAddress,
			'emailAddress'           => $this->mailAddress,
		];

		$private_key = openssl_pkey_new(["digest_alg"       => "sha512",
										 "private_key_bits" => 4096,
										 "private_key_type" => OPENSSL_KEYTYPE_RSA,
										]);

		$csr = openssl_csr_new($distinguished_names, $private_key, ['digest_alg' => 'sha256']);
		$x509 = openssl_csr_sign($csr, null, $private_key, 365, ['digest_alg' => 'sha256']);

		openssl_pkey_export_to_file($private_key, $dir.$keyFileName);
		openssl_x509_export_to_file($x509, $dir.$certFileName);

		response:

		return [
			'crt' => [
				'filename' => $certFileName,
				'dir' => $dir,
				'path' => $dir.$certFileName,
			],
			'key' => [
				'filename' => $keyFileName,
				'dir' => $dir,
				'path' => $dir.$keyFileName,
			],
			'details' => $this->getCertificateDetails($dir.$certFileName),
		];

	}

	/**
	 * Retrieve the certificate details as an associative array.
	 * @param string $absPath
	 * @return array|false
	 */
	public function getCertificateDetails(string $absPath): array|false {
		return openssl_x509_parse(file_get_contents($absPath));
	}


	/**
	 * Create a new instance
	 */
	protected function __construct(){}

	/**
	 * Load the access token and its expiration from custom caching storage.
	 * @param string $clientId affected client ID
	 * @return array|null token data or null on failure
	 */
	protected function getCache(string $clientId): array|null {

		// provide your own logic to load the cached data pending on the clientId

		/* a simple example for a file based caching

		$cacheFile = sprintf('%s/cache/azure-ad-tokens.json', MY_DOC_ROOT);
		$cache = json_decode(file_get_contents($cacheFile), true);

		*/

		return $cache[$clientId] ?? null;
	}

	/**
	 * Save the access token and its expiration to the custom caching storage.
	 * @param string $clientId affected client ID
	 * @param array $tokenData details about the access token
	 * @return $this
	 */
	protected function setCache(string $clientId, array $tokenData): self{
		// implement your own logic to store the token data in your cache pending on the clientId
		// keep in mind, that the accessToken is a JWT and needs some space (string 2000)

		/* a simple example for a file based caching

		$cacheFile = sprintf('%s/cache/azure-ad-tokens.json', MY_DOC_ROOT);
		$cache = json_decode(file_get_contents($cacheFile), true);
		$cache[$clientId] = $tokenData;
		file_put_contents($cacheFile, json_encode($cache, JSON_PRETTY_PRINT));

		*/

		return $this;
	}

	/**
	 * Get the token data array from runtime or storage cache.
	 * @param string $clientId affected client ID
	 * @return array|null token data or null on failure
	 */
	protected function getTokenData(string $clientId): array|null {

		$tokenData = $this->getRuntimeCache($clientId) ?: $this->getCache($clientId);

		if($tokenData && $tokenData['expiresAt'] > time()){
			$this->setRuntimeCache($clientId, $tokenData);
			return $tokenData;
		}

		return null;
	}

	/**
	 * Set token data to runtime and storage cache.
	 * @param string $clientId affected client ID
	 * @param array $tokenData details about the access token
	 * @return $this
	 */
	protected function setTokenData(string $clientId, array $tokenData): self {

		$this->setRuntimeCache($clientId, $tokenData);
		$this->setCache($clientId, $tokenData);

		return $this;
	}

	/**
	 * Get token from runtime cache.
	 * @param string $clientId affected client ID
	 * @return array|null token data or null on failure
	 */
	protected function getRuntimeCache(string $clientId): array|null {
		return $this->runtimeCache[$clientId] ?? null;
	}

	/**
	 * Set token data to runtime cache.
	 * @param string $clientId affected client ID
	 * @param array $tokenData details about the access token
	 * @return $this
	 */
	protected function setRuntimeCache(string $clientId, array $tokenData): self{
		$this->runtimeCache[$clientId] = $tokenData;
		return $this;
	}

	/**
	 * Check if the parameters for the available authentication logics and scope are complete.
	 * @return void
	 */
	private function checkAuthenticationScopes(): void{

		$this->clientSecretParams = false;
		$this->certificateParams = false;

		if($this->clientId && $this->clientSecret && $this->tenantId && $this->tenantName){
			$this->clientSecretParams = true;
		}
		if($this->clientId && $this->tenantId && $this->tenantName && $this->x509Cert && $this->x509Key){
			if(empty(trim($this->x509Cert))){
				$this->addError('X509 certificate is not set');
			}
			if(empty(trim($this->x509Key))){
				$this->addError('X509 key is not set');
			}
			if(!$this->hasErrors()){
				if($this->createX509Fingerprint()){
					$this->certificateParams = true;
				}
			}
		}
		if(!$this->clientSecretParams && !$this->certificateParams){
			$this->addError('Configuration values are incomplete or invalid');
		}
	}

	/**
	 * Add a new error message to the collection.
	 * @param string $message
	 * @return void
	 */
	private function addError(string $message): void{
		$this->errors[] = $message;
	}

	/**
	 * Create the base64 encoded fingerprint fo the x509 certificate.
	 * @return bool
	 */
	private function createX509Fingerprint(): bool{
		try{
			$x509 = $this->x509Cert;
			// read the cert string
			$cert = openssl_x509_read($x509);
			// get the sha1 fingerprint --> binary NOT hexadecimal!
			$hex = openssl_x509_fingerprint($cert, 'sha1', true);
			// convert binary thumbprint to base64 and remove trailing symbols
			$this->x509Fingerprint = rtrim(base64_encode($hex), '=');
			return true;
		}catch(Exception $e){
			$this->addError($e->getMessage());
			return false;
		}
	}

	/**
	 * Retrieve a new access token via API, using the clientID + clientSecret authentication logic.
	 * @return array|null token data or null on failure
	 */
	private function retrieveAccessToken__clientSecret(): array|null{

		if(!$this->clientSecretParams) return null;

		try{

			$params = [
				'form_params' => [
					'grant_type'    => 'client_credentials',
					'client_id'     => $this->clientId,
					'client_secret' => $this->clientSecret,
					'scope'         => sprintf('https://%s/.default', $this->tenantName), // maybe https://outlook.office.com/SMTP.Send, https://outlook.office365.com/.default
				],
			];

			$this->fetchTokenFromMicrosoftLogin($params);

			return $this->getRuntimeCache($this->mailAddress);

		} catch (ClientException $e) {
			$errorData = json_decode($e->getResponse()->getBody()->getContents());
			$this->addError('Authentication Error: '.print_r($errorData, true));
			return null;
		}catch(GuzzleException $e){
			$this->addError('Authentication Error: '.$e->getMessage());
			return null;
		}catch(Exception $e){
			$this->addError('Authentication Error: '.$e->getMessage());
			return null;
		}
	}

	/**
	 * Retrieve a new access token via API, using the clientID + certificate authentication logic.
	 * @return array|null token data or null on failure
	 */
	private function retrieveAccessToken__certificate(): array|null{

		if(!$this->certificateParams) return null;

		try{

			$claims = [
				// Issued at timestamp
				'iat' => strtotime('now'),
				// Expires timestamp
				'exp' => strtotime('+10 minutes'),
				// Issuer
				'iss' => $this->clientId,
				// Subject Claim
				'sub' => $this->clientId,
				// Audience
				'aud' => sprintf($this->authenticationUrl, $this->tenantId),
			];

			$headers = [
				'typ' => 'JWT',
				'alg' => 'RS256',
				'x5t' => $this->x509Fingerprint,
			];

			$privateKey = $this->x509Key;
			$jwt = JWT::encode($claims, $privateKey, 'RS256', null, $headers);

			$params = [
				'form_params' => [
					'grant_type'            => 'client_credentials',
					'client_id'             => $this->clientId,
					'scope'                 => 'https://outlook.office.com/.default', // full mail access (IMAP, POP, SMTP)
					'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
					'client_assertion'      => $jwt,
				]
			];

			$this->fetchTokenFromMicrosoftLogin($params);

			return $this->getRuntimeCache($this->mailAddress);

		} catch (ClientException $e) {
			$errorData = json_decode($e->getResponse()->getBody()->getContents());
			$this->addError('Authentication Error (client): '.print_r($errorData, true));
			return null;
		}catch(GuzzleException $e){
			$this->addError('Authentication Error (guzzle): '.$e->getMessage());
			return null;
		}catch(Exception $e){
			$this->addError('Authentication Error (php): '.$e->getMessage());
			return null;
		}
	}

	/**
	 * Execute the authentication request and handle the response.
	 * @param array $params
	 * @return void
	 * @throws GuzzleException
	 */
	private function fetchTokenFromMicrosoftLogin(array $params): void{

		$client = new Client();
		$response = $client->request('POST', sprintf($this->authenticationUrl, $this->tenantId), $params);
		$data = $response->getBody()->getContents();
		$jsonData = json_decode($data);

		$tokenData = [
			'accessToken' => $jsonData->access_token,
			'expiresAt' => strtotime('+'.($jsonData->expires_in - 5).' seconds'),
		];
		// update run time scope
		$this->setTokenData($this->mailAddress, $tokenData);
	}


}
