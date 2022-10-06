<?php
namespace App\Model\Database\Language;
use App\Model\Database\AbstractEntitySearch;
use App\Entity\Language;
use App\Repository\LanguageRepository;

/**
 * @method Language|null byId(string $id)
 * @property  LanguageRepository repository
 */
class LanguageSearch extends AbstractEntitySearch
{
    public function byIso(string $iso) :?Language{
        return $this->repository->findOneBy([
            'iso' => $iso
        ]);
    }
}
