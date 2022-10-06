<?php
namespace App\Model\Database\User;
use App\Entity\Language;
use App\Model\Database\AbstractEntityCreate;
use App\Entity\User;
use App\Repository\UserRepository;


/**
 * @property UserRepository repository
 */
class UserCreate extends AbstractEntityCreate
{
    public function byParams(
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
        bool $isAdmin = false,
        ?string $accessToken = null,
        ?string $activationToken = null,
    ): User{
        $user = (new User())
            ->setEmail($email)
            ->setPassword($password)
            ->setGender($gender)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setStreet($street)
            ->setCity($city)
            ->setCountry($country)
            ->setPostcode($postcode)
            ->setLanguageId($languageId)
            ->setLanguage($this->entityManager->getReference(Language::class, $languageId))
            ->setIsAdmin($isAdmin)
            ->setAccessToken($accessToken)
            ->setActive(false)
            ->setActivationToken($activationToken);
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
        return $user;
    }
}
