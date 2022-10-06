<?php


namespace App\Model\Authentication;


interface AuthenticationInterface
{
    const ERROR_VALID = 0;
    const ERROR_TIME_EXPIRED = 1;
    const ERROR_WRONG_TOKEN = 2;
    const ERROR_SIGNATURE_INVALID = 3;
    const ERROR_INVALID_TYPE = 4;

    /**
     * @param string $userIdentifier    => used to identifier the user
     * @param string|null $accessToken  => used if StaticConfig::AUTH_STATELESS is false -> should be used to compare this token with one in the Database
     * @param bool $longValidTime       => if true StaticConfig::AUTH_TOKEN_VALID_LONG is used as expire time
     * @return string
     * @description generates authentification token. This Token is Stateless if $token = NULL, this means it does not need a database
     * connection to verify that the token is legit
     */
    public function generateToken(string $userIdentifier,bool $longValidTime, ?string $accessToken = NULL) :string;

    /**
     * @param int $userIdentifier
     * @param string|null $accessToken
     * @return string
     */
    public function generateForgotPasswordToken(int $userIdentifier, ?string $accessToken = NULL) :string;

    /**
     * @param string $jwt
     * @param string|null $token =>  used if staticConfig::AUTH_STATELESS is false -> will check for simulerity
     * @return bool
     * @description checks if the token is valid and if $token is provided it checks if the $token is the same
     */
    public function isValid(string $jwt, ?string $token = NULL):bool;
    public function isForgotPasswordValid(string $jwt, ?string $token =NULL):bool; // for ForgotPasswordTokens

    /**
     * @param string $jwt
     * @return string|null
     * @description returns the userId which is saved inside the $JWT
     */
    public function getUserId(string $jwt):?string;

    /**
     * @param string $jwt
     * @return string|null
     * @description checks if the token is supposed for long remember or not
     */
    public function isLongValidTime(string $jwt):bool;

    /**
     * @param string $jwt
     * @param bool $force
     * @return string
     * @description renews the token ( if necessery based on the StaticConfigs::AUTH_RENEW_TOKEN_BEFORE Time )
     */
    public function refreshToken(string $jwt, bool $force = false) :string;

    /**
     * @return array
     * @description returns one of the ERROR_* Constants ( the reason why the key is not valid )
     */
    public function getErrors():array;
}
