<?php

namespace App\Controller\Api\User;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAuthController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractApiAuthController
{
    #[Route(
        '/api/User/User/index',
        name: 'api_user_user_user',
        methods: ['GET']
    )]
    /**
     * payload: [
     * ],
     * response: [
     *      'success' => <bool>,
     *      'user' => [
     *          ... array with userData
     *      ]
     * ]
     */
    public function index(Request $request, SerializerInterface $serializer) :Response{
        $this->params['user']    = json_decode(
            $serializer->serialize($this->user, 'json', [
                'groups' => [
                    'user',
                    'language'
                ]
            ])
        );
        $this->params['success'] = true;
        return $this->response();
    }

    #[Route(
        '/api/User/User/logoff',
        name: 'api_user_user_logoff',
        methods: ['POST']
    )]
    /**
     * payload: [
     * ],
     * response: [
     *      'success' => <bool>,
     * ]
     */
    public function logoff() :Response{
        $this->facade->Database()->User()->update()->setAccessToken($this->userId, (!StaticConfigs::AUTH_STATELESS) ? bin2hex(random_bytes(16)) : NULL);
        $this->params['success'] = true;
        return $this->response();
    }

    #[Route(
        '/api/User/User/locale',
        name: 'api_user_user_locale',
        methods: ['PUT']
    )]
    /**
     * payload: [
     *      'language',   <string>,   (required), iso code of language
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Locale not found
     *      ]
     * ]
     */
    public function changeLocale(Request $request) :Response{
        $success    = false;
        $codes      = [];
        if(empty($language = $this->facade->Database()->Language()->search()->byIso($request->get('language')))){
            $codes[] = "F1";
        }else{
            $this->facade->Database()->User()->update()->setLanguage($this->userId, $language->getId());
            $success = true;
        }

        $this->params['success'] = $success;
        $this->params['codes']   = $codes;
        return $this->response();
    }

    #[Route(
        '/api/User/User/update',
        name: 'api_user_user_update',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'user'             <object>,   (required), User Data
     *       {
     *          'email':        <string>,   (required), valid email
     *          'lang':         <string>,   (required), (<iso code from Language Entity>)
     *          'gender':       <string>,   (required), ('f', 'm', null)
     *          'firstname':    <string>,   (required), min 3 Character
     *          'lastname':     <string>,   (required), min 3 Character
     *          'street':       <string>,   (required), min 3 Character
     *          'country':      <string>,   (required), min 3 Character
     *          'postcode':     <int>,      (required),
     *          'city':         <string>,   (required), min 3 Character
     *       }
     *      'changePassword',   <bool>,     (required), should the password be updated
     *      'newPassword',      <string>,   (required), new Password to set
     *      'oldPassword',      <string>,   (required), old Password to set
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes': [
     *          "F1" => email not valid,
     *          "F2" => email already in use
     *          "F3" => password security to low
     *          "F4" => firstname or lastname shorter then 3 character
     *          "F5" => wrong gender (no offense but we just support 3 out of 62 Genders :/ )
     *          "F6" => Language could not be detected
     *          "F7" => Address not valid
     *          "F8" => Wrong Password
     *      ]
     * ]
     */
    public function update(Request $request, SerializerInterface $serializer) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade = $this->facade->Database();
        // check mail
        if (empty($request->get('email')) || !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $codes[] = "F1";
        } else {
            $user = $databaseFacade->User()->search()->byEmail($request->get('email'));
            if (!empty($user) && $user->getId() !== $this->userId) {
                $codes[] = "F2";
            }
        }
        // check password
        if((bool) $request->get('changePassword')) {
            if (strlen($request->get('newPassword')) < 8)
                $codes[] = 'F3';
            if(!password_verify($request->get('password'),$this->user->getPassword()))
                $codes[] = "F8";
        }

        // check username
        if(
            strlen($request->get('firstname')) < 3 ||
            strlen($request->get('lastname')) < 3
        )
            $codes[] = 'F4';

        // check gender
        if(
            $request->get('gender') != 'd' &&
            $request->get('gender') != 'f' &&
            $request->get('gender') != 'm'
        )
            $codes[] = 'F5';

        // check langauge
        if(
            empty($request->get('lang')) ||
            empty($language = $databaseFacade->Language()->search()->byIso($request->get('lang')))
        )
            $codes[] = 'F6';

        // check address
        if(
            strlen($request->get('street')) < 3 ||
            strlen($request->get('country')) < 3 ||
            strlen($request->get('city')) < 3 ||
            !is_numeric($request->get('postcode'))
        )
            $codes[] = 'F6';

        if(empty($codes)){
            $user = $databaseFacade->User()->update()->byParams(
                $this->userId,
                $request->get('email'),
                password_hash($request->get('newPassword'),StaticConfigs::PASSWORD_HASH,['cost' => 8]),
                $request->get('gender'),
                $request->get('firstname'),
                $request->get('lastname'),
                $request->get('street'),
                $request->get('city'),
                $request->get('country'),
                (int) $request->get('postcode'),
                $language->getId(),
                (bool) $request->get('changePassword')
            );
            $this->params['user']   = json_decode(
                $serializer->serialize($user, 'json', [
                    'groups' => [
                        'user',
                        'language'
                    ]
                ])
            );
            $success = true;
        }

        $this->params['success'] = $success;
        $this->params['codes']   = $codes;
        return $this->response();
    }
}
