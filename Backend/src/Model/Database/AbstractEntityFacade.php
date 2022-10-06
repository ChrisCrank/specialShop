<?php

namespace App\Model\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
abstract class AbstractEntityFacade
{
    protected EntityManager $entityManager;
    protected EntityRepository $repository;
    protected string $entityName;

    function __construct(EntityManager $entityManager)
    {
        $this->entityName = ucFirst($this->getEntityName());
        $entityName = $this->entityName;
        $entityClass = 'App\\Entity\\'.$entityName;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($entityClass);
    }

    public abstract function getEntityName() :string;

    public function search():AbstractEntitySearch{
        return $this->load('Search');
    }
    public function delete():AbstractEntityDelete{
        return $this->load('Delete');
    }
    public function update():AbstractEntityUpdate{
        return $this->load('Update');
    }
    public function create():AbstractEntityCreate{
        return $this->load('Create');
    }

    private function load(string $type) {
        $entityConnectionClass = 'App\\Model\\Database\\'.$this->entityName.'\\'.$this->entityName.$type;
        return new $entityConnectionClass($this->repository, $this->entityManager);
    }
}
