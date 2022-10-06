<?php

namespace App\Controller\Api\Admin;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAdminAuthController;
use App\Model\Image\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractApiAdminAuthController
{
    #[Route(
        '/api/Admin/Category/add',
        name: 'api_admin_category_add',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'img',          <file>,    (optional), thumbnail of category
     *      'sort',         <int>,       (required), sort of the category
     *      'parent',       <int>,       (optional), id of parent id
     *      'active',       <bool>,      (optional), is Category active, default FALSE
     *      'name',         <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'description',  <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Not all required Parameters are submitted
     *          F2 = Language not valid
     *          F3 = Title already exsisting
     *          F4 = img not valid
     *      ]
     * ]
     */
    public function index(Request $request) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade =  $this->facade->Database();

        $img      = $_FILES['img'] ?? null;
        $active   = (bool) $request->get('active') ?? false;
        $parentId = (int) $request->get('parent') ?? null;

        // check for required params
        if(
            empty($request->get('sort')) ||
            empty($request->get('name')) ||
            empty($request->get('name')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('description')) ||
            empty($request->get('description')[StaticConfigs::DEFAULT_STORE_LANG])
        )
            $codes[] = "F1";

        // check if name already exsist with same parent
        $translationName = [];
        $translationDescription = [];
        foreach($request->get('name') as $isoCode => $value){
            // get languageId
            $language = $databaseFacade->Language()->search()->byIso($isoCode);
            if(empty($language))
                $codes[] = "F2";
            else{
                $categories = $databaseFacade->Category()->search()->byNameAndParent(
                    $value, $language->getId(), $parentId
                );
                if(!empty($categories)){
                    $codes[] = "F3";
                    $this->params['language'] = $isoCode;
                    break;
                }
                $description = $request->get('description')[$isoCode] ?? '';
                $translationName[$language->getId()]        = $value == 'null' || empty($value) ? null : $value;
                $translationDescription[$language->getId()] = $description == 'null' || empty($description) ? null : $description;
            }
        }

        // check img
        if(!Image::checkImg($img))
            $codes[] = "F4";

        if(empty($codes)){
            // save image
            if(!empty($img)) {
                $folderName = $this->saveImg($img);
            }

            // get Path of Category
            $path = $this->buildPathStr($parentId);

            // add Category to Database
            $category = $databaseFacade->Category()->create()->byParams(
                (int) $request->get('sort'),
                $translationName,
                $translationDescription,
                $path,
                (!empty($img)) ? $this->getImgFolderBaseName().'/'.$folderName : null,
                $parentId,
                $active
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }

    #[Route(
        '/api/Admin/Category/edit',
        name: 'api_admin_category_edit',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'id',           <string>,    (required), id of category
     *      'img',          <file>,      (optional), thumbnail of category
     *      'sort',         <int>,       (required), sort of the category
     *      'parent',       <int>,       (optional), id of parent id
     *      'active',       <bool>,      (optional), is Category active, default FALSE
     *      'name',         <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     *      'description',  <array>,     (required), [<iso> => text, <iso2> => translatedText] (StaticConfigs::DEFAULT_STORE_LANG required)
     * ],
     * response: [
     *      'success' => <bool>,
     *      'codes' => [
     *          F1 = Not all required Parameters are submitted
     *          F2 = Language not valid
     *          F3 = Title already exsisting
     *          F4 = img not valid
     *      ]
     * ]
     */
    public function edit(Request $request) :Response{
        $codes          = [];
        $success        = false;
        $databaseFacade =  $this->facade->Database();

        $img      = $_FILES['img'] ?? null;
        $active   = (bool) $request->get('active') ?? false;
        $parentId = (int) $request->get('parent') ?? null;
        $category = $databaseFacade->Category()->search()->byId((int) $request->get('id'));

        // check for required params
        if(
            empty($category) ||
            empty($request->get('sort')) ||
            empty($request->get('name')) ||
            empty($request->get('name')[StaticConfigs::DEFAULT_STORE_LANG]) ||
            empty($request->get('description')) ||
            empty($request->get('description')[StaticConfigs::DEFAULT_STORE_LANG])
        )
            $codes[] = "F1";

        // check if name already exsist with same parent
        $translationName = [];
        $translationDescription = [];
        foreach($request->get('name') as $isoCode => $value){
            // get languageId
            $language = $databaseFacade->Language()->search()->byIso($isoCode);
            if(empty($language))
                $codes[] = "F2";
            else{
                $categories = $databaseFacade->Category()->search()->byNameAndParent(
                    $value, $language->getId(), $parentId
                );
                if(!empty($categories) && $categories[0]->getId() != (int) $request->get('id')){
                    $codes[] = "F3";
                    $this->params['language'] = $isoCode;
                    break;
                }
                $description = $request->get('description')[$isoCode] ?? '';
                $translationName[$language->getId()]        = $value == 'null' || empty($value) ? null : $value;
                $translationDescription[$language->getId()] = $description == 'null' || empty($description) ? null : $description;
            }
        }

        // check img
        if(!Image::checkImg($img))
            $codes[] = "F4";

        if(empty($codes)){
            // save image
            if(!empty($img)) {
                // delete old image
                $this->facade->Image()->deleteImages($category->getImg());
                $folderName = $this->saveImg($img);
            }

            // get Path of Category
            $path = $this->buildPathStr($parentId);

            // add Category to Database
            $category = $databaseFacade->Category()->update()->byParams(
                (int) $request->get('id'),
                (int) $request->get('sort'),
                $translationName,
                $translationDescription,
                $path,
                (!empty($img)) ? $this->getImgFolderBaseName().'/'.$folderName : $category->getImg(),
                $parentId,
                $active
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }

    #[Route(
        '/api/Admin/Category/delete/{id}',
        name: 'api_admin_category_delete',
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

        $category = $databaseFacade->Category()->search()->byId($id);

        // check for required params
        if(
            empty($category)
        )
            $codes[] = "F1";

        if(empty($codes)){
            // delete old image of this category
            $this->facade->Image()->deleteImages($category->getImg());

            // add Category to Database
            $databaseFacade->Category()->delete()->byId(
                $id
            );
            $success = true;
        }

        $this->params['codes']   = $codes;
        $this->params['success'] = $success;
        return $this->response();
    }




    private function saveImg(?array $img) :string{
        $folderName     = bin2hex(random_bytes(10));
        $fileBaseName   = 1;
        if (!is_dir($this->getUploadPath() . $folderName))
            mkdir($this->getUploadPath() . $folderName);
        $this->facade->Image()->convert($img, $fileBaseName, $this->getImgFolderBaseName().'/'.$folderName);
        return $folderName;
    }

    private function buildPathStr(?string $parentId = null) :?string{
        $path = [];
        if(!empty($parentId)){
            $cParentId = $parentId;
            while(!empty($cParentId) && !empty($parentCategory = $this->facade->Database()->Category()->search()->byId($cParentId))){
                $path[]     = $parentCategory->getId();
                $cParentId  = $parentCategory->getParentId();
            }
        }
        return (empty($path)) ? null : '|'.implode('|', array_reverse($path)).'|';
    }

    private function getUploadPath(): string{
        return realpath(StaticConfigs::SAVE_IMAGE_DIR).'/'.$this->getImgFolderBaseName().'/';
    }
    private function getImgFolderBaseName(): string{
        return 'Category';
    }
}
