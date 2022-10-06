<?php
namespace App\Model\Database\User;
use App\Entity\Language;
use App\Entity\User;
use App\Model\Database\AbstractEntityUpdate;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;

/**
 * @property UserRepository repository
 */
class UserUpdate extends AbstractEntityUpdate
{
    public function setActive(int $userId) :self{
        $user = $this->repository->find($userId);
        $user->setActive(true);
        $this->entityManager->flush();
        return $this;
    }

    public function setAccessToken(int $userId, string $code) :self{
        $user = $this->repository->find($userId);
        $user->setAccessToken($code);
        $this->entityManager->flush();
        return $this;
    }

    public function setPassword(int $userId, string $password) :self{
        $user = $this->repository->find($userId);
        $user->setPassword($password);
        $this->entityManager->flush();
        return $this;
    }

    public function setLanguage(int $userId, string $languageId) :self{
        $user = $this->repository->find($userId);
        $user->setLanguageId($languageId);
        $this->entityManager->flush();
        return $this;
    }

    public function byParams(
        int $id,
        string $email,
        string $password,
        string $gender,
        string $firstname,
        string $lastname,
        string $street,
        string $city,
        string $country,
        int $postcode,
        int $languageId,
        bool $changePassword = false
    ): User{
        $user = $this->repository->find($id);
        $user
            ->setEmail($email)
            ->setGender($gender)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setStreet($street)
            ->setCity($city)
            ->setCountry($country)
            ->setPostcode($postcode)
            ->setLanguageId($languageId)
            ->setLanguage($this->entityManager->getReference(Language::class, $languageId));
        if($changePassword)
            $user->setPassword($password);
        $this->entityManager->flush($user);
        return $user;
    }
}
