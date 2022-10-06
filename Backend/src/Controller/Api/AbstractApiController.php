<?php

namespace App\Controller\Api;

use App\Config\StaticConfigs;
use App\Model\Facade;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Flex\Response;

abstract class AbstractApiController extends AbstractController
{
    protected Facade $facade;
    protected array $headers = [];
    protected array $params  = [];
    protected string $lang   = StaticConfigs::DEFAULT_STORE_LANG;
    protected array $requestHeaders;
    public function __construct(Facade $facade, ContainerInterface $container){
        $this->facade           = $facade;
        $this->requestHeaders   = getallheaders();
        $this->lang = (!empty($this->requestHeaders[StaticConfigs::LOCALE_HEADER])) ? $this->requestHeaders[StaticConfigs::LOCALE_HEADER] : $this->lang;
        $this->setContainer($container);
    }

    protected function response(): JsonResponse{
        return $this->json($this->params, 200, $this->headers);
    }
}
