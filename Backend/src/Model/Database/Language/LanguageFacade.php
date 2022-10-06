<?php

namespace App\Model\Database\Language;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method LanguageSearch search()
 * @method LanguageDelete delete()
 * @method LanguageUpdate update()
 * @method LanguageCreate create()
 */
class LanguageFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'language';
    }
}
