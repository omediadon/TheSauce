<?php

namespace App\Validation;

use Cake\Chronos\Chronos;
use InvalidArgumentException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Ramsey\Uuid\Uuid;
use UnexpectedValueException;

final class JwtAuth{
	/**
	 * @var string The issuer name
	 */
	private string $issuer;

	/**
	 * @var int Max lifetime in seconds
	 */
	private $lifetime;

	/**
	 * @var string The private key
	 */
	private $privateKey;

	/**
	 * @var string The public key
	 */
	private $publicKey;

	/**
	 * @var Sha256 The signer
	 */
	private $signer;

	/**
	 * The constructor.
	 *
	 * @param string $issuer     The issuer name
	 * @param int    $lifetime   The max lifetime
	 * @param string $privateKey The private key as string
	 * @param string $publicKey  The public key as string
	 */
	public function __construct(string $issuer, int $lifetime, string $privateKey, string $publicKey){
		$this->issuer     = $issuer;
		$this->lifetime   = $lifetime;
		$this->privateKey = $privateKey;
		$this->publicKey  = $publicKey;
		$this->signer     = new Sha256();
	}

	/**
	 * Get JWT max lifetime.
	 *
	 * @return int The lifetime in seconds
	 */
	public function getLifetime(): int{
		return $this->lifetime;
	}

	/**
	 * Create JSON web token.
	 *
	 * @param array $context
	 *
	 * @return string The jwt
	 * @throws UnexpectedValueException
	 *
	 */
	public function createJwt(array $context): string{
		$issuedAt = Chronos::now()
		                   ->getTimestamp();

		// (JWT ID) Claim, a unique identifier for the JWT
		return (new Builder())->issuedBy($this->issuer)
		                      ->identifiedBy(Uuid::uuid4()
		                                         ->toString(), true)
		                      ->issuedAt($issuedAt)
		                      ->canOnlyBeUsedAfter($issuedAt)
		                      ->expiresAt($issuedAt + $this->lifetime)
		                      ->withClaim('context', $context)
		                      ->getToken($this->signer, new Key($this->privateKey));
	}

	/**
	 * Validate the access token.
	 *
	 * @param string $accessToken The JWT
	 *
	 * @return bool The status
	 */
	public function validateToken(string $accessToken): bool{
		$token = $this->createParsedToken($accessToken);

		if(!$token->verify($this->signer, $this->publicKey)){
			// Token signature is not valid
			return false;
		}

		// Check whether the token has not expired
		$data = new ValidationData();
		$data->setCurrentTime(Chronos::now()
		                             ->getTimestamp());
		$data->setIssuer($token->getClaim('iss'));
		$data->setId($token->getClaim('jti'));

		return $token->validate($data);
	}

	/**
	 * Parse token.
	 *
	 * @param string $token The JWT
	 *
	 * @return Token The parsed token
	 * @throws InvalidArgumentException
	 *
	 */
	public function createParsedToken(string $token): Token{
		return (new Parser())->parse($token);
	}
}