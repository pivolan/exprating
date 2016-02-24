<?php
/**
 * Date: 24.02.16
 * Time: 17:35
 */

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function getByUserQuery(User $user)
    {
        $query = $this->createQueryBuilder('node')
            ->select('node')
            ->innerJoin('node.admins', 'u', null)
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery();
        return $query;
    }
}