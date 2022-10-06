<?php

namespace App\Controller\Api\User;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class LoginController extends AbstractApiController
{
    #[Route(
        '/api/User/Login/login',
        name: 'api_user_login_login',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'email',     <string>,   (required), email
     *      'password',  <string>,   (required), password
     *      'rememberMe',<bool>,     (optional), default false
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          "F1": invalid credentials,
     *          "F3": not activated
     *      ]
     *      'user' => [
     *          ... array with userData
     *      ]
     *      <headers> => [
     *          StaticConfigs::AUTH_SERVER_TOKEN_HEADER => JWT Token
     *      ]
     * ]
     */
    public function login(Request $request, SerializerInterface $serializer) :Response{
        $success        = false;
        $codes          = [];

        $databaseFacade = $this->facade->Database();

        $rememberMe = $request->get('rememberMe') ?? false;
        $user       = $databaseFacade->User()->search()->byEmail($request->get('email'));

        if(!empty($user) && (!$user->isActive())) $codes[] = 'F3';
        if(!empty($user) && password_verify($request->get('password'),$user->getPassword())){
            if(empty($codes)) {
                $accessToken    = (!StaticConfigs::AUTH_STATELESS) ? StaticConfigs::generateAccessToken() : NULL;
                $token          = $this->facade->Authentication()->generateToken($user->getId(), $rememberMe, $accessToken);
                if (!StaticConfigs::AUTH_STATELESS) {
                    $databaseFacade->User()->update()->setAccessToken($user->getId(), $accessToken);
                }
                $this->headers[StaticConfigs::AUTH_SERVER_TOKEN_HEADER] = $token;

                // merge Cart
                $cartItems = $_COOKIE[StaticConfigs::CART_COOKIE_NAME] ?? null;
                if(!empty($cartItems)){
                    $cartItems = json_decode($cartItems, true);
                    foreach($cartItems as $item){
                        $databaseFacade->CartItems()->update()->upsertItem(
                            $user->getId(),
                            $item['productId'],
                            $item['quantity']
                        );
                    }
                }
                // load language
                $success                = true;
                $this->params['user']   = json_decode(
                    $serializer->serialize($user, 'json', [
                        'groups' => [
                            'user',
                            'language'
                        ]
                    ])
                );
            }
        }else{
            $codes[] = "F1";
        }
        $this->params['success'] = $success;
        $this->params['codes'] = $codes;
        return $this->response();
    }

    #[Route(
        '/api/User/Login/Forgot',
        name: 'api_user_login_forgot',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'email',     <string>,   (required), email
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          "F1": Email not found,
     *      ]
     * ]
     */
    public function forgot(Request $request) :Response{
        $success        = false;
        $codes          = [];

        $databaseFacade = $this->facade->Database();
        $user           = $databaseFacade->User()->search()->byEmail($request->get('email'));
        if (empty($user))
            $codes[] = "F1";
        else {
            /**
             * TODO: send as email
             */
            $this->params['token'] = $this->facade->Authentication()->generateForgotPasswordToken($user->getId());
            $success = true;
        }

        $this->params['success'] = $success;
        $this->params['codes'] = $codes;
        return $this->response();
    }

    #[Route(
        '/api/User/Login/Reset',
        name: 'api_user_login_reset',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'password',   <string>,   (required), email
     *      'token',      <string>,   (required), code
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          'F2': TOKEN INVALID,
     *          'F3': Password to Weak,
     *      ]
     * ]
     */
    public function reset(Request $request) :Response{
        $success        = false;
        $codes          = [];
        $auth           = $this->facade->Authentication();

        $validToken     = $auth->isForgotPasswordValid($request->get('token'));
        if(!$validToken)
            $codes[] = "F1";
        if (strlen($request->get('password')) < 8)
            $codes[] = 'F3';

        if(empty($codes)){
            $userId = $auth->getUserId($request->get('token'));
            $password = password_hash($request->get('password'), StaticConfigs::PASSWORD_HASH,['cost' => 8]);
            $this->facade->Database()->User()->update()->setPassword($userId, $password);
            $success = true;
        }

        $this->params['success'] = $success;
        $this->params['codes'] = $codes;
        return $this->response();
    }
}
