<?php
namespace App\Model;
use App\Model\Database\Database;
use App\Model\Authentication\Authentication;
use App\Model\Image\Image;
use App\Model\ProductCalculator\ProductCalculator;
use Doctrine\ORM\EntityManager;

class Factory
{
    private EntityManager $entityManager;
    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }

    protected function createDatabase() :Database{
        return new Database($this->entityManager);
    }
    protected function createAuthentication() :Authentication{
        return new Authentication();
    }
    protected function createImage() :Image{
        return new Image();
    }

    protected function createProductCalculator() :ProductCalculator{
        return new ProductCalculator($this->createImage());
    }
}
