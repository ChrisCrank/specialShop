<?php

namespace App\Controller\Api\Category;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAuthController;
use App\Entity\Category;
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
        '/api/Category/Category/all/{fetchInactive}/{fetchTranslations}',
        name: 'api_category_category_all',
        methods: ['GET']
    )]
    /**
     * payload: [
     *      'fetchInactive',         <bool>,   (optional), if this is true Categories which are inactive will also be fetched
     *      'fetchTranslations',     <bool>,   (optional), if this is true Categories will come with a translationObject
     * ],
     * response: [
     *      'success' => <bool>,
     *      'categories' => <array>, -> object with categories
     *      'codes' => [
     *          "F1": Email not found,
     *      ]
     * ]
     */
    public function getAllCategories(Request $request,SerializerInterface $serializer, bool $fetchInactive = false, bool $fetchTranslations = false){
        $fetchInactive      = $this->user && $this->user->isIsAdmin() && $fetchInactive;
        $fetchTranslations  = $this->user && $this->user->isIsAdmin() && $fetchTranslations;
        $categories         = $this->facade->Database()->Category()->search()->all($fetchInactive);
        $this->calculateDefaultData($categories, $this->lang, true);
        $this->params['success'] = true;
        $this->params['Categories'] = json_decode(
            $serializer->serialize($categories, 'json', [
                'groups' => [
                    'category',
                    ($fetchTranslations) ? 'category_translation' : '',
                    'language'
                ]
            ])
        );
        return $this->response();
    }


    private function calculateDefaultData(array $categories, string $language = null, bool $loadImages = false){
        /**
         * @var Category $category
         */
        foreach($categories as $category){
            // set translations
            foreach($category->getTranslation() as $translation){
                if($translation->getLanguage()->getIso() == $language) {
                    $category->setName($translation->getName());
                    $category->setDescription($translation->getDescription());
                    break;
                }
                if($translation->getLanguage()->getIso() == StaticConfigs::DEFAULT_STORE_LANG){
                    $category->setName($translation->getName());
                    $category->setDescription($translation->getDescription());
                }
            }

            // load image information
            $category->setCalculatedImages($this->facade->Image()->loadImages($category->getImg()));
        }
        return $categories;
    }
}
