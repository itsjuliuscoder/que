<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 27/7/2019
 * Time: 12:21 AM
 */

namespace que\security\jwt;

/**
 * This class is representation of decoded JSON Web Token (JWT).
 *
 */
class TokenDecoded
{

    /**
     * Array containing token's header elements.
     */
    protected $header;
    
    /**
     * Array containing token's payload elements.
     */
    protected $payload;

    /**
     * @param array|null $header
     * @param array|null $payload
     */
    public function __construct(?array $header = [], ?array $payload = [])
    {
        if (($isNullTtl = is_null($ttl = config('auth.jwt.ttl'))) && $payload) unset($payload['exp']);

        if ($payload && !$isNullTtl && !array_key_exists('exp', $payload)) {
            $payload['exp'] = (($payload['iat'] ?? APP_TIME) + $ttl);
        }

        $header['imt'] = !array_key_exists('exp', $payload);
        $this->payload = $payload;
        $this->header = $header;
    }

    /**
     * Gets array with token's payload.
     * 
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Sets array with token's payload.
     * 
     * @param array $payload
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * Gets array with token's header.
     * 
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * Sets array with token's header.
     * 
     * @param array $header
     */
    public function setHeader(array $header): void
    {
        $this->header = $header;
    }

    /**
     * Performs auto encoding.
     *
     * @param string $secret Secret Key used for signing token.
     * @param string|null $algorithm Optional algorithm to be used when algorithm is not yet defined in token's header.
     * @param int|null $leeway
     * @return TokenEncoded
     * @throws Exceptions\EmptyTokenException
     * @throws Exceptions\InsecureTokenException
     * @throws Exceptions\IntegrityViolationException
     * @throws Exceptions\InvalidClaimTypeException
     * @throws Exceptions\InvalidStructureException
     * @throws Exceptions\MissingClaimException
     * @throws Exceptions\SigningFailedException
     * @throws Exceptions\TokenExpiredException
     * @throws Exceptions\TokenInactiveException
     * @throws Exceptions\UndefinedAlgorithmException
     * @throws Exceptions\UnsupportedAlgorithmException
     * @throws Exceptions\UnsupportedTokenTypeException
     */
    public function encode(string $secret, ?string $algorithm = null, ?int $leeway = null) : TokenEncoded
    {
        return JWT::encode($this, $secret, $algorithm, $leeway);
    }
}
