<?php

namespace App\Controller\Api\Admin;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAdminAuthController;
use App\Model\FileManager\FileManager;
use App\Model\Image\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractApiAdminAuthController
{
    #[Route(
        '/api/Admin/Product/add',
        name: 'api_admin_product_add',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'img',              <file>,      (required), thumbnail of product
     *      'sort',             <int>,       (required), sort of the product inside the category
     *      'stock',            <int>,       (required), Stock of the PRoduct
     *      'price',            <float>,     (required), Price of the product
     *      'productNumber',    <float>,     (required), ProductNumber
     *      'categories',       <string>,    (required), ids of Categories for the Product comma sperated
     *      'active',           <bool>,      (optional), is Product active, default FALSE
     *      'name',             <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'description',      <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'shortDescription', <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Not all required Parameters are submitted
     *          F2 = Language not valid
     *          F3 = Categories not exsist
     *          F4 = img not valid
     *          F5 = name already exist
     *      ]
     * ]
     */
    public function index(Request $request) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade =  $this->facade->Database();

        $img      = $_FILES['img'] ?? null;
        $active   = (bool) $request->get('active') ?? false;
        $categorieIds = explode(',',$request->get('categories')) ?? null;

        // check for required params
        if(
            empty($request->get('sort')) ||
            empty($request->get('stock')) ||
            empty($request->get('productNumber')) ||
            strlen($request->get('productNumber')) < 3 ||
            empty($request->get('price')) ||
            empty($request->get('name')) ||
            empty($request->get('name')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('description')) ||
            empty($request->get('description')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('shortDescription')) ||
            empty($request->get('shortDescription')[StaticConfigs::DEFAULT_STORE_LANG])
        )
            $codes[] = "F1";

        // check if languageId are valid
        $translationName = [];
        $translationDescription = [];
        $translationShortDescription = [];
        foreach($request->get('name') as $isoCode => $value){
            // get languageId
            $language = $databaseFacade->Language()->search()->byIso($isoCode);
            if(empty($language))
                $codes[] = "F2";
            else{
                $product = $databaseFacade->Product()->search()->byName(
                    $value, $language->getId()
                );
                if(!empty($product)){
                    $codes[] = "F3";
                    $this->params['language'] = $isoCode;
                    break;
                }
                $description        = $request->get('description')[$isoCode] ?? '';
                $shortDescription   = $request->get('shortDescription')[$isoCode] ?? '';

                $translationName[$language->getId()]            = $value == 'null' || empty($value) ? null : $value;
                $translationDescription[$language->getId()]     = $description == 'null' || empty($description) ? null : $description;
                $translationShortDescription[$language->getId()]= $shortDescription == 'null' || empty($shortDescription) ? null : $shortDescription;
            }
        }

        // check for exsisting categories
        if(empty($categorieIds))
            $codes[] = "F3";

        foreach($categorieIds as $categoryId) {
            if(empty($databaseFacade->Category()->search()->byId($categoryId)))
                $codes[] = "F3";
        }

        // check img
        if(!Image::checkImg($img, true))
            $codes[] = "F4";
        if(empty($codes)){
            // save image
            $folderName = $this->saveImg($img);
            // check for images inside $translationDescription and move from /tmp/ to /product/
            foreach($translationDescription as &$value)
                $value = $this->saveTmpImages($value);
            // add Product to Database
            $product = $databaseFacade->Product()->create()->byParams(
                (int) $request->get('sort'),
                (int) $request->get('stock'),
                (float) str_replace(',','.',$request->get('price')),
                $request->get('productNumber'),
                $translationName,
                $translationDescription,
                $translationShortDescription,
                $this->getImgFolderBaseName().'/'.$folderName,
                $categorieIds,
                $active
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }

    #[Route(
        '/api/Admin/Product/edit',
        name: 'api_admin_product_edit',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'id',                <int>,       (required), id of the Product
     *      'img',               <file>,      (optional), thumbnail of product
     *      'sort',              <int>,       (required), sort of the product inside the category
     *      'stock',             <int>,       (required), Stock of the PRoduct
     *      'price',             <float>,     (required), Price of the product
     *      'productNumber',     <float>,     (required), ProductNumber
     *      'categories',        <string>,    (required), ids of Categories for the Product comma sperated
     *      'active',            <bool>,      (optional), is Product active, default FALSE
     *      'name',              <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'description',       <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'shortDescription',  <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Not all required Parameters are submitted
     *          F2 = Language not valid
     *          F3 = Categories not exsist
     *          F4 = img not valid
     *          F5 = name already exist
     *      ]
     * ]
     */
    public function edit(Request $request) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade =  $this->facade->Database();
        $product        = $databaseFacade->Product()->search()->byId((int) $request->get('id'));

        $img      = $_FILES['img'] ?? null;
        $active   = (bool) $request->get('active') ?? false;
        $categorieIds = explode(',',$request->get('categories')) ?? null;

        // check for required params
        if(
            empty($product) ||
            empty($request->get('sort')) ||
            empty($request->get('stock')) ||
            empty($request->get('productNumber')) ||
            strlen($request->get('productNumber')) < 3 ||
            empty($request->get('price')) ||
            empty($request->get('name')) ||
            empty($request->get('name')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('description')) ||
            empty($request->get('description')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('shortDescription')) ||
            empty($request->get('shortDescription')[StaticConfigs::DEFAULT_STORE_LANG])
        )
            $codes[] = "F1";

        // check if languageId are valid
        $translationName = [];
        $translationDescription = [];
        $translationShortDescription = [];
        foreach($request->get('name') as $isoCode => $value){
            // get languageId
            $language = $databaseFacade->Language()->search()->byIso($isoCode);
            if(empty($language))
                $codes[] = "F2";
            else{
                $rProduct = $databaseFacade->Product()->search()->byName(
                    $value, $language->getId()
                );
                if(!empty($rProduct) && $rProduct->getId() !== $product->getId()){
                    $codes[] = "F3";
                    $this->params['language'] = $isoCode;
                    break;
                }
                $description      = $request->get('description')[$isoCode] ?? '';
                $shortDescription = $request->get('shortDescription')[$isoCode] ?? '';

                $translationName[$language->getId()]            = $value == 'null' || empty($value) ? null : $value;
                $translationDescription[$language->getId()]     = $description == 'null' || empty($description) ? null : $description;
                $translationShortDescription[$language->getId()]= $shortDescription == 'null' || empty($shortDescription) ? null : $shortDescription;
            }
        }

        // check for exsisting categories
        if(empty($categorieIds))
            $codes[] = "F3";

        foreach($categorieIds as $categoryId) {
            if(empty($databaseFacade->Category()->search()->byId($categoryId)))
                $codes[] = "F3";
        }

        // check img
        if(!Image::checkImg($img))
            $codes[] = "F4";

        if(empty($codes)){
            // save image
            if(!empty($img)){
                // delete old image
                $this->facade->Image()->deleteImages($product->getImg());
                $folderName = $this->saveImg($img);
            }

            $newUrls = [];
            $oldUrls = [];
            foreach($translationDescription as &$value) {
                // check for images inside $translationDescription and move from /tmp/ to /product/
                $value = $this->saveTmpImages($value);
                // find all images which are currently saved
                preg_match_all(
                    '/\/img\/uploads\/'.$this->getImgFolderBaseName().'\/(?<foldername>.*)\//U',
                    $value,
                    $newDescriptionUrl
                );
                $newUrls = array_merge($newUrls, $newDescriptionUrl);
            }
            foreach($product->getTranslation() as $translation){
                // find all images which are saved in previous version
                preg_match_all(
                    '/\/img\/uploads\/'.$this->getImgFolderBaseName().'\/(?<foldername>.*)\//U',
                    $translation->getDescription(),
                    $oldDescriptionUrl
                );
                $oldUrls = array_merge($oldUrls, $oldDescriptionUrl);
            }
            // delete alĺ Images which where replaces/deleted
            if(isset($oldUrls['foldername'])) {
                foreach ($oldUrls['foldername'] as $name) {
                    if(isset($newUrls['foldername'])){
                        if(!in_array($name, $newUrls['foldername'])){
                            // delete image
                            $this->facade->Image()->deleteImages($this->getImgFolderBaseName().'/'.$name);
                        }
                    }else
                        $this->facade->Image()->deleteImages($this->getImgFolderBaseName().'/'.$name);
                }
            }

            // add Product to Database
            $product = $databaseFacade->Product()->update()->byParams(
                (int) $request->get('id'),
                (int) $request->get('sort'),
                (int) $request->get('stock'),
                (float) str_replace(',','.',$request->get('price')),
                $request->get('productNumber'),
                $translationName,
                $translationDescription,
                $translationShortDescription,
                (!empty($img)) ? $this->getImgFolderBaseName().'/'.$folderName : $product->getImg(),
                $categorieIds,
                $active
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }


    #[Route(
        '/api/Admin/Product/delete/{id}',
        name: 'api_admin_product_delete',
        methods: ['DELETE']
    )]
    /**
     * payload: [
     *      'id',           <string>,    (required), id of category
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Not all required Parameters are submitted
     *      ]
     * ]
     */
    public function delete(Request $request, int $id) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade =  $this->facade->Database();

        $product = $databaseFacade->Product()->search()->byId($id);

        // check for required params
        if(
            empty($product)
        )
            $codes[] = "F1";

        if(empty($codes)){
            // delete old image of this product
            $this->facade->Image()->deleteImages($product->getImg());
            // delete all Images inside Description
            foreach($product->getTranslation() as $translation){
                preg_match_all(
                    '/\/img\/uploads\/'.$this->getImgFolderBaseName().'\/(?<foldername>.*)\//U',
                    $translation->getDescription(),
                    $urls
                );
                if(isset($urls['foldername'])) {
                    foreach ($urls['foldername'] as $name) {
                        $this->facade->Image()->deleteImages($this->getImgFolderBaseName().'/'.$name);
                    }
                }
            }

            // add Category to Database
            $databaseFacade->Product()->delete()->byId(
                $id
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }

    private function saveTmpImages(string $string) :string{
        $matches = [];
        preg_match_all(
            '/\/img\/uploads\/'.ImageController::TEMP_IMAGE_UPLOAD_FOLDER.'\/(?<foldername>.*)\//U',
            $string,
            $matches
        );
        if(isset($matches['foldername'])) {
            foreach ($matches['foldername'] as $name) {
                $tmpPath = realpath(StaticConfigs::SAVE_IMAGE_DIR) . DS . ImageController::TEMP_IMAGE_UPLOAD_FOLDER . DS . $name;
                if (is_dir($tmpPath) && !is_dir($this->getUploadPath() . $name))
                    FileManager::rcopy($tmpPath, $this->getUploadPath() . $name);
                // clear tmp folder
                FileManager::rrmdir($tmpPath);
            }
            $string = str_replace(
                '/img/uploads/'.ImageController::TEMP_IMAGE_UPLOAD_FOLDER.'/',
                '/img/uploads/'.$this->getImgFolderBaseName().'/',
                $string
            );
        }
        return $string;
    }

    private function saveImg(?array $img) :string{
        $folderName     = bin2hex(random_bytes(10));
        $fileBaseName   = 1;
        if (!is_dir($this->getUploadPath() . $folderName))
            mkdir($this->getUploadPath() . $folderName);
        $this->facade->Image()->convert($img, $fileBaseName, $this->getImgFolderBaseName().'/'.$folderName);
        return $folderName;
    }

    private function getUploadPath(): string{
        return realpath(StaticConfigs::SAVE_IMAGE_DIR).'/'.$this->getImgFolderBaseName().'/';
    }
    private function getImgFolderBaseName(): string{
        return 'Product';
    }
}
