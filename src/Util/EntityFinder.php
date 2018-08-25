<?php
namespace App\Util;

use App\Entity\Team;
use App\Exception\NotFoundException;
use Doctrine\Common\Persistence\ObjectManager;

class EntityFinder
{
    public static function findBySlug(ObjectManager $em, string $entityClass, string $slug): Object
    {
        $repository = $em->getRepository($entityClass);
        $list = $repository->findBySlug($slug);
        if(empty($list)) {
            throw new NotFoundException(sprintf('there is no %s with slug %s', $entityClass, $slug));
        }
        return array_shift($list);
    }

}
