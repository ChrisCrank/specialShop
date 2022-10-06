<?php

namespace App\Controller\Api\Product;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAuthController;
use App\Controller\Api\AbstractApiController;
use App\Entity\Category;
use App\Entity\Product;
use App\Model\Facade;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class IndexController extends AbstractApiAuthController
{

    public function __construct(Facade $facade, ContainerInterface $container)
    {
        parent::__construct($facade, $container, false);
    }

    #[Route(
        '/api/Product/Product/all/{fetchInactive}/{fetchTranslations}/{categoryId}/{offset}/{limit}',
        name: 'api_product_product_all',
        defaults: ['categoryId'=>null, 'offset'=>0, 'limit'=>0],
        methods: ['GET'],
    )]
    /**
     * payload: [
     *      'fetchInactive',         <bool>,   (optional), if this is true Products which are inactive will also be fetched
     *      'fetchTranslations',     <bool>,   (optional), if this is true Products will come with a translationObject
     *      'categoryId',            <bool>,   (optional), products from categoryId
     * ],
     * response: [
     *      'success' => <bool>,
     *      'Products' => <array>, -> object with products
     * ]
     */
    public function getAllProducts(
        Request $request,
        SerializerInterface $serializer,
        bool $fetchInactive = false,
        bool $fetchTranslations = false,
        ?int $categoryId = null,
        int $offset = 0,
        int $limit = 0
    ){
        $fetchInactive      = $this->user && $this->user->isIsAdmin() && $fetchInactive;
        $fetchTranslations  = $this->user && $this->user->isIsAdmin() && $fetchTranslations;
        if(empty($categoryId))
            $products = $this->facade->Database()->Product()->search()->all($fetchInactive, $offset, $limit);
        else {
            // get all Childs of Category
            $categoryIds = $this->facade->Database()->Category()->search()->getChildIds($categoryId);
            $products = $this->facade->Database()->Product()->search()->byCategoryId(
                array_merge($categoryIds,[$categoryId]),
                $fetchInactive,
                $offset,
                $limit
            );
        }

        $this->facade->ProductCalculator()->calculateDefaultData($products, $this->lang);
        $this->params['success'] = true;
        $this->params['Products'] = json_decode(
            $serializer->serialize($products, 'json', [
                'groups' => [
                    'product',
                    ($fetchTranslations) ? 'product_translation' : '',
                    'language'
                ]
            ])
        );
        return $this->response();
    }

    #[Route(
        '/api/Product/Product/count/{fetchInactive}/{categoryId}',
        name: 'api_product_product_count',
        defaults: ['categoryId'=>null],
        methods: ['GET'],
    )]
    /**
     * payload: [
     *      'fetchInactive',         <bool>,   (optional), if this is true Products which are inactive will also be fetched
     *      'categoryId',            <bool>,   (optional), products from categoryId
     * ],
     * response: [
     *      'success' => <bool>,
     *      'ProductCount' => <array>, -> object with products
     * ]
     */
    public function getProductCount(
        Request $request,
        SerializerInterface $serializer,
        bool $fetchInactive = false,
        ?int $categoryId = null
    ){
        $fetchInactive      = $this->user && $this->user->isIsAdmin() && $fetchInactive;
        if(empty($categoryId))
            $count = $this->facade->Database()->Product()->search()->allCount($fetchInactive);
        else
            $count = $this->facade->Database()->Product()->search()->byCategoryIdCount($categoryId, $fetchInactive);
        $this->params['success'] = true;
        $this->params['ProductCount'] = $count;
        return $this->response();
    }

    #[Route(
        '/api/Product/Product/id/{productId}/{fetchInactive}/{fetchTranslations}',
        name: 'api_product_product_id',
        defaults: ['fetchInactive'=>false, 'fetchTranslations' => false],
        methods: ['GET'],
    )]
    /**
     * payload: [
     *      'fetchInactive',         <bool>,   (optional), if this is true Products which are inactive will also be fetched
     *      'fetchTranslations',     <bool>,   (optional), if this is true Products will come with a translationObject
     * ],
     * response: [
     *      'success' => <bool>,
     *      'Product' => <array>, -> object with products
     * ]
     */
    public function getProductById(Request $request,SerializerInterface $serializer,string $productId, bool $fetchInactive = false, bool $fetchTranslations = false){
        $fetchInactive      = $this->user && $this->user->isIsAdmin() && $fetchInactive;
        $fetchTranslations  = $this->user && $this->user->isIsAdmin() && $fetchTranslations;
        if(is_numeric($productId))
            $product = $this->facade->Database()->Product()->search()->byIdentifier($productId, $fetchInactive);
        else
            $product = $this->facade->Database()->Product()->search()->byName($productId, $fetchInactive);

        if(!empty($product)) {
            $product = $this->facade->ProductCalculator()->calculateDefaultData([$product], $this->lang)[0];
            $product = json_decode(
                $serializer->serialize($product, 'json', [
                    'groups' => [
                        'product',
                        ($fetchTranslations) ? 'product_translation' : '',
                        'language',
                        'categoryIds'
                    ]
                ])
            );
        }
        $this->params['success'] = true;
        $this->params['Product'] = $product ?? [];
        return $this->response();
    }


    #[Route(
        '/api/Product/Product/recent/{offset}/{limit}',
        name: 'api_product_product_recent',
        defaults: ['offset'=>0, 'limit' => 0],
        methods: ['GET'],
    )]
    /**
     * payload: [
     *      'fetchInactive',         <bool>,   (optional), if this is true Products which are inactive will also be fetched
     *      'fetchTranslations',     <bool>,   (optional), if this is true Products will come with a translationObject
     * ],
     * response: [
     *      'success' => <bool>,
     *      'Products' => <array>, -> object with products
     * ]
     */
    public function getRecentProducts(Request $request,SerializerInterface $serializer, int $offset = 0, int $limit = 0){
        $products = $this->facade->Database()->Product()->search()->recent($offset, $limit);

        if(!empty($products)) {
            $products = $this->facade->ProductCalculator()->calculateDefaultData($products, $this->lang);
            $products = json_decode(
                $serializer->serialize($products, 'json', [
                    'groups' => [
                        'product',
                        'language',
                        'categoryIds'
                    ]
                ])
            );
        }
        $this->params['Products'] = $products ?? [];
        $this->params['success'] = true;
        return $this->response();
    }
}
