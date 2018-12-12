<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUsername($user){

        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:User');
        $query = $repository->createQueryBuilder('u')
            ->select("f.username")
            ->where("f.idUser = :u")
            ->setParameter('u',$user)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }


}