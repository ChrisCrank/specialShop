<?php

namespace App\Controller\Api;

use App\Config\StaticConfigs;
use App\Entity\User;
use App\Model\Facade;
use Psr\Container\ContainerInterface;

class AbstractApiAdminAuthController extends AbstractApiAuthController
{
    public function __construct(Facade $facade, ContainerInterface $container)
    {
        parent::__construct($facade, $container);
        if(!$this->user->isIsAdmin()){
            http_response_code(401);
            echo json_encode([
                'success'   => false,
                'msg'       => 'Unauthorized'
            ]);
            exit(401);
        }
    }
}
