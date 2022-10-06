<?php
namespace App\Model\Database;

abstract class AbstractEntityDelete extends AbstractEntityConnection
{
    public function byId(int $id) {
        $this->entityManager->remove($this->repository->find($id));
        $this->entityManager->flush();
    }
}
