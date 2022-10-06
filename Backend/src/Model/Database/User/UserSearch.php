<?php
namespace App\Model\Database\User;
use App\Model\Database\AbstractEntitySearch;
use App\Entity\User;
use App\Repository\UserRepository;

/**
 * @method User|null byId(int $id)
 * @property UserRepository repository
 */
class UserSearch extends AbstractEntitySearch
{
    public function byEmail(string $email, bool $loadLanguageAssociation = false) :?User{
        return $this->repository->findOneBy([
            'email' => $email
        ]);
    }
    public function byActivationCode(string $code) :?User{
        return $this->repository->findOneBy([
            'activationToken' => $code
        ]);
    }
}
