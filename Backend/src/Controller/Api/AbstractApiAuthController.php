<?php

namespace App\Controller\Api;

use App\Config\StaticConfigs;
use App\Entity\User;
use App\Model\Facade;
use Psr\Container\ContainerInterface;

class AbstractApiAuthController extends AbstractApiController
{
    protected ?int $userId = null;
    protected ?User $user = null;

    /**
     * @param Facade $facade
     * @param ContainerInterface $container
     * @param bool $sendResponse -> if true the Controller will respond with 401 Status Code if token is invalid
     */
    public function __construct(Facade $facade, ContainerInterface $container, bool $sendResponse = true)
    {
        parent::__construct($facade, $container);
        $token   = $this->requestHeaders[StaticConfigs::AUTH_CLIENT_TOKEN_HEADER] ?? null;
        $success = false;
        if (!empty($token)){
            $auth           = $this->facade->Authentication();
            $this->userId   = (int) $auth->getUserId($token);
            if (!empty($this->userId)) {
                $accessToken = NULL;
                if(!StaticConfigs::AUTH_STATELESS){
                    // request access-token from db
                    $user = $this->facade->Database()->User()->search()->byId($this->userId);
                    if(!empty($user)){
                        $accessToken    = $user->getAccessToken();
                        $this->user     = $user;
                        $this->lang     = (empty($this->requestHeaders[StaticConfigs::LOCALE_HEADER])) ?
                            $user->getLanguage()->getIso() ?? $this->lang :
                            $this->requestHeaders[StaticConfigs::LOCALE_HEADER];
                    }
                }
                $valid = $this->facade->Authentication()->isValid($token, $accessToken);
                if($valid){
                    // renew token
                    $token      = $this->facade->Authentication()->refreshToken($token);
                    $success    = true;
                    $this->headers[StaticConfigs::AUTH_SERVER_TOKEN_HEADER] = $token;
                }
            }
        }
        if(!$success && $sendResponse){
            http_response_code(401);
            echo json_encode([
                'success'   => false,
                'msg'       => 'Unauthorized'
            ]);
            exit(401);
        }
    }
}
