<?php
namespace App\Controller\Api\User;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractApiController
{
    #[Route(
        '/api/User/Register/register',
        name: 'api_user_register_register',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'email':        <string>,   (required), valid email
     *      'password':     <string>,   (required), min 8 Character
     *      'lang':         <string>,   (required), (<iso code from Language Entity>)
     *      'gender':       <string>,   (required), ('f', 'm', null)
     *      'firstname':    <string>,   (required), min 3 Character
     *      'lastname':     <string>,   (required), min 3 Character
     *      'street':       <string>,   (required), min 3 Character
     *      'country':      <string>,   (required), min 3 Character
     *      'postcode':     <int>,      (required),
     *      'city':         <string>,   (required), min 3 Character
     * ],
     * response: [
     *      'success': <bool>,
     *      'activationToken': <string>, // this is normaly send via Email to ensure that the email is valid
     *      'codes': [
     *          "F1" => email not valid,
     *          "F2" => email already in use
     *          "F3" => password security to low
     *          "F4" => firstname or lastname shorter then 3 character
     *          "F5" => wrong gender (no offense but we just support 3 out of 62 Genders :/ )
     *          "F6" => Language could not be detected
     *          "F7" => Address not valid
     *      ]
     * ]
     */
    public function register(Request $request): Response
    {
        $codes          = [];
        $success        = false;
        $databaseFacade = $this->facade->Database();

        // check mail
        if (empty($request->get('email')) || !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $codes[] = "F1";
        } else {
            if (!empty($databaseFacade->User()->search()->byEmail($request->get('email')))) {
                $codes[] = "F2";
            }
        }
        // check password
        if (strlen($request->get('password')) < 8)
            $codes[] = 'F3';

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
            // register user
            /**
             * TODO: SETUP SMTP Server and send the activationToken inside the Email not inside the response
             */
            $activationToken                 = StaticConfigs::generateActivationToken();
            $this->params['activationToken'] = $activationToken;
            $databaseFacade->User()->create()->byParams(
                $request->get('email'),
                password_hash($request->get('password'),StaticConfigs::PASSWORD_HASH,['cost' => 8]),
                $request->get('gender'),
                $request->get('firstname'),
                $request->get('lastname'),
                $request->get('street'),
                $request->get('city'),
                $request->get('country'),
                (int) $request->get('postcode'),
                $language->getId(),
                false,
                null,
                $activationToken
            );
            $success = true;
        }

        $this->params['success'] = $success;
        $this->params['codes'] = $codes;
        return $this->response();
    }

    #[Route(
    '/api/User/Register/activate',
    name: 'api_user_register_activate',
    methods: ['POST']
    )]
    /**
     * payload: [
     *      'code',     <string>,   (required), Activation Code to authetification the Email of the user
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 => no valid code
     *      ]
     * ]
     */
    public function activate(Request $request) :Response{
        $success        = false;
        $databaseFacade = $this->facade->Database();

        if(!empty($request->get('code'))){
            $user = $databaseFacade->User()->search()->byActivationCode($request->get('code'));
            if(!empty($user)) {
                $databaseFacade->User()->update()->setActive($user->getId());
                $success = true;
            }
        }
        $this->params['success'] = $success;
        $this->params['codes'] = (!$success) ? ['F1'] : [];
        return $this->response();
    }

    #[Route(
        '/api/User/Register/resend',
        name: 'api_user_register_resend',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'mail',     <string>,   (required), Email of user
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 => email not found
     *      ]
     * ]
     */
    public function resend(Request $request) :Response{
        $success        = false;
        $databaseFacade = $this->facade->Database();
        if(!empty($request->get('email'))){
            $user = $databaseFacade->User()->search()->byEmail($request->get('email'));
            $this->params['activationToken'] = $user->getActivationToken();
            if(!empty($user)) {
                $success = true;
            }
        }
        $this->params['success'] = $success;
        $this->params['codes'] = (!$success) ? ['F1'] : [];
        return $this->response();
    }
}
