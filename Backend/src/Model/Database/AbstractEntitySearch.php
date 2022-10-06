<?php
namespace App\Model\Database;

abstract class AbstractEntitySearch extends AbstractEntityConnection
{
    public function byId(int $id){
        return $this->repository->findOneBy([
            'id' => $id
        ]);
    }
}
