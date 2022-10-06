<?php
namespace App\Model\Database;
use App\Model\Database\CartItems\CartItemsFacade;
use App\Model\Database\Category\CategoryFacade;
use App\Model\Database\Language\LanguageFacade;
use App\Model\Database\Order\OrderFacade;
use App\Model\Database\Product\ProductFacade;
use App\Model\Database\User\UserFacade;
use App\Entity\Language;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class Database{
    private EntityManager $entityManager;
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function User() :UserFacade{
        return new UserFacade($this->entityManager);
    }

    public function Language() :LanguageFacade{
        return new LanguageFacade($this->entityManager);
    }

    public function Category() :CategoryFacade{
        return new CategoryFacade($this->entityManager);
    }

    public function Product() :ProductFacade{
        return new ProductFacade($this->entityManager);
    }

    public function CartItems() :CartItemsFacade{
        return new CartItemsFacade($this->entityManager);
    }

    public function Order() :OrderFacade{
        return new OrderFacade($this->entityManager);
    }
}
