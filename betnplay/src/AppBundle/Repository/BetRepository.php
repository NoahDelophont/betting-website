<?php

namespace AppBundle\Repository;

/**
 * BetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BetRepository extends \Doctrine\ORM\EntityRepository
{
    public function getThreeLastBets($user){

        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:Bet');
        $query = $repository->createQueryBuilder('m')
             ->where("m.idUser = :u")
             ->setMaxResults(3)
             ->setParameter('u',$user)
             ->getQuery();
        $result = $query->getResult();

        return $result;
    }


    public function getFifteenLastBets($user){

        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:Bet');
        $query = $repository->createQueryBuilder('m')
            ->where("m.idUser = :u")
            ->setMaxResults(30)
            ->setParameter('u',$user)
            ->getQuery();
        $result = $query->getResult();

        return $result;
    }


}
