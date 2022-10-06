<?php

namespace App\Model\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class AbstractEntityConnection
{
    protected EntityRepository $repository;
    protected EntityManager $entityManager;
    public function __construct(EntityRepository $repository, EntityManager $entityManager){
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
}
