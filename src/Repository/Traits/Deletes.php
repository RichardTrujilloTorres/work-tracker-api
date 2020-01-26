<?php

namespace App\Repository\Traits;

use App\Exception\NotFoundException;

/**
 * Trait Deletes.
 */
trait Deletes
{
    /**
     * @param int $id
     *
     * @throws NotFoundException
     */
    public function delete(int $id)
    {
        $resource = $this->find($id);
        if (!$resource) {
            throw new NotFoundException('Could not find resource with ID '.$id);
        }

        $this->getEntityManager()->remove($resource);
        $this->getEntityManager()->flush();
    }
}
