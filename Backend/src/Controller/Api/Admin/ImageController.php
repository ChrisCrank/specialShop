<?php

namespace App\Controller\Api\Admin;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAdminAuthController;
use App\Model\Image\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractApiAdminAuthController
{
    const TEMP_IMAGE_UPLOAD_FOLDER = 'tmp';

    #[Route(
        'api/Admin/Image/add',
        name: 'api_admin_image_add',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'upload',       <file>,      (required), image which should be uploaded
     * ],
     * response: [
     *      'success' => <bool>,
     *      'urls' => [
     *          'default' => default img url
     *          <size> => url
     *      ]
     *      'message' => <errorMessage>,
     * ]
     */
    public function index(Request $request) :Response{
        $message    = "";
        $img        = $_FILES['upload'];
        $success    = true;
        $urls       = [];
        if(empty($img)) {
            $success = false;
            $message = "img is empty";
        }
        //check img
        if(!Image::checkImg($img, true)){
            $success = false;
            $message = "img is not valid";
        }

        if($success){
            $folderName = bin2hex(random_bytes(10));
            $fileBaseName = 1;
            if(!is_dir($this->getUploadPath().$folderName))
                mkdir($this->getUploadPath().$folderName);
            $res = $this->facade->Image()->convert($img, $fileBaseName, 'tmp/'.$folderName);
            $urls['default'] =  StaticConfigs::IMAGE_URL.'uploads/tmp/'.$folderName.'/'.$fileBaseName.'-org.'.strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
            foreach($res as $image){
                $urls[$image['size']] = StaticConfigs::IMAGE_URL.'uploads/'.$image['url'];
            }
        }

        $this->params['message']    = $message;
        $this->params['urls']       = $urls;
        $this->params['success']    = $success;
        return $this->response();
    }

    private function getUploadPath(): string{
        return realpath(StaticConfigs::SAVE_IMAGE_DIR).'/'.self::TEMP_IMAGE_UPLOAD_FOLDER.'/';
    }
}
