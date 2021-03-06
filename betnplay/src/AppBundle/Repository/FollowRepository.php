<?php

namespace AppBundle\Repository;

/**
 * FollowRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FollowRepository extends \Doctrine\ORM\EntityRepository
{
    public function getFollowers($user){

        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:Follow');
        $query = $repository->createQueryBuilder('f')
            ->where("f.idUser1 = :u")
            ->setParameter('u',$user)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }


}