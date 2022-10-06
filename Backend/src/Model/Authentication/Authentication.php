<?php


namespace App\Model\Authentication;



use App\Config\StaticConfigs;

class Authentication implements AuthenticationInterface
{
    private array $errors = [];
    public function generateToken(string $userIdentifier,bool $longValidTime, ?string $accessToken = NULL) :string
    {
        $token      = base64_encode(random_bytes(12));
        $secret     = StaticConfigs::AUTH_SECTRET_TOKEN;
        $validTime  = ($longValidTime) ? StaticConfigs::AUTH_TOKEN_VALID_LONG : StaticConfigs::AUTH_TOKEN_VALID_SHORT;

        // RFC-defined structure
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        $payload = [
            "token"     => $token,
            "created"   => time(),
            "exp"       => time() + $validTime,
            "data"      => [
                "id"        => $userIdentifier,
                "token"     => $accessToken,
                "long"      => $longValidTime,
                "type"      => "l" // flags as login token
            ]
        ];
        $header     = $this->base64url_encode(json_encode($header));
        $payload    = $this->base64url_encode(json_encode($payload));
        $jwt        = $header . '.' . $payload;
        $signature  = $this->base64url_encode(hash_hmac('SHA256', $jwt, base64_decode($secret), true));

        return $jwt . '.' . $signature;
    }

    public function generateForgotPasswordToken(int $userIdentifier, ?string $accessToken = NULL) :string{
        $token      = base64_encode(random_bytes(12));
        $secret     = StaticConfigs::AUTH_SECTRET_TOKEN;
        $validTime  = StaticConfigs::AUTH_TOKEN_VALID_PASSWORD;

        // RFC-defined structure
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];

        // whatever you want
        $payload = [
            "token"     => $token,
            "created"   => time(),
            "exp"       => time() + $validTime,
            "data"      => [
                "id"        => $userIdentifier,
                "token"     => $accessToken,
                "type"      => "f",
            ]
        ];
        $header     = $this->base64url_encode(json_encode($header));
        $payload    = $this->base64url_encode(json_encode($payload));
        $jwt        = $header . '.' . $payload;
        $signature  = $this->base64url_encode(hash_hmac('SHA256', $jwt, base64_decode($secret), true));

        return $jwt . '.' . $signature;
    }

    public function isValid(string $jwt, ?string $token = NULL):bool
    {
        $secret = StaticConfigs::AUTH_SECTRET_TOKEN;
        // split the token
        $tokenParts     = explode('.', $jwt);
        $header         = base64_decode($tokenParts[0]);
        $payload        = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // check if expired
        $expiration     = json_decode($payload,true)['exp'];
        $tokenExpired   = ($expiration - time()) < 0;

        // check for same token
        $providedToken  = json_decode($payload,true)['data']['token'] ?? NULL;
        $tokenValid     = $providedToken === $token;

        // check for type
        $providedType  = json_decode($payload,true)['data']['type'] ?? NULL;
        $typeValid     = $providedType === 'l';

        // build a signature based on the header and payload using the secret
        $header     = $this->base64url_encode($header);
        $payload    = $this->base64url_encode($payload);
        $jwt        = $header . '.' . $payload;
        $signature  = $this->base64url_encode(hash_hmac('SHA256', $jwt, base64_decode($secret), true));
        $signatureValid = ($signature === $signatureProvided);

        $this->addToErrors($signatureValid, !$tokenExpired, $tokenValid, $typeValid);
        return $signatureValid && !$tokenExpired && $tokenValid && $typeValid;
    }

    public function isForgotPasswordValid(string $jwt, ?string $token = NULL):bool
    {
        $secret = StaticConfigs::AUTH_SECTRET_TOKEN;
        // split the token
        $tokenParts         = explode('.', $jwt);
        $header             = base64_decode($tokenParts[0]);
        $payload            = base64_decode($tokenParts[1]);
        $signatureProvided  = $tokenParts[2];

        // check if expired
        $expiration     = json_decode($payload,true)['exp'];
        $tokenExpired   = ($expiration - time()) < 0;

        // check for same token
        $providedToken  = json_decode($payload,true)['data']['token'] ?? NULL;
        $tokenValid     = $providedToken === $token;

        // check for type
        $providedType   = json_decode($payload,true)['data']['type'] ?? NULL;
        $typeValid      = $providedType === 'f';

        // build a signature based on the header and payload using the secret
        $header     = $this->base64url_encode($header);
        $payload    = $this->base64url_encode($payload);
        $jwt        = $header . '.' . $payload;
        $signature  = $this->base64url_encode(hash_hmac('SHA256', $jwt, base64_decode($secret), true));
        $signatureValid = ($signature === $signatureProvided);

        $this->addToErrors($signatureValid, !$tokenExpired, $tokenValid, $typeValid);
        return $signatureValid && !$tokenExpired && $tokenValid && $typeValid;
    }

    public function getUserId(string $jwt): ?string
    {
        $tokenParts     = explode('.', $jwt);
        $payload        = base64_decode($tokenParts[1]);
        return json_decode($payload,true)['data']['id'] ?? NULL;
    }
    public function isLongValidTime(string $jwt) :bool{
        $tokenParts     = explode('.', $jwt);
        $payload        = base64_decode($tokenParts[1]);
        return json_decode($payload,true)['data']['long'] ?? false;
    }

    public function refreshToken(string $jwt, bool $force = false) :string{
        // split the token
        $tokenParts     = explode('.', $jwt);
        $payload        = base64_decode($tokenParts[1]);

        // check if renew is needet
        $expiration     = json_decode($payload,true)['exp'];
        $needRefresh    = ($expiration - time()) < StaticConfigs::AUTH_RENEW_TOKEN_BEFORE;

        // get user Id
        $userId         = json_decode($payload,true)['data']['id'] ?? NULL;
        $longValidTime  = json_decode($payload,true)['data']['long'] ?? false;
        $token          = json_decode($payload,true)['data']['token'] ?? false;

        if($needRefresh || $force){
            $token = $this->generateToken($userId, $longValidTime, $token);
        } else {
            $token = $jwt;
        }
        return $token;
    }

    private function base64url_encode(string $data):string{
        $b64 = base64_encode($data);
        if ($b64 === false) {
            return false;
        }
        $url = strtr($b64, '+/', '-_');
        return rtrim($url, '=');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function addToErrors(bool $signatureValid,bool $notExpired,bool $tokenValid,bool $typeValid) :void {
        if(!$signatureValid)
            $this->errors[] = self::ERROR_SIGNATURE_INVALID;
        if(!$notExpired)
            $this->errors[] = self::ERROR_TIME_EXPIRED;
        if(!$tokenValid)
            $this->errors[] = self::ERROR_WRONG_TOKEN;
        if(!$typeValid)
            $this->errors[] = self::ERROR_INVALID_TYPE;
    }
}
