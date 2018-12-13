<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Follow
 *
 * @ORM\Table(name="follow")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FollowRepository")
 */
class Follow
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_userone", type="integer")
     */
    private $idUser1;

    /**
     * @var int
     *
     * @ORM\Column(name="id_usertwo", type="integer")
     */
    private $idUser2;

    public function setIdUser1($id)
    {
        $this->idUser1 = $id;

        return $this;
    }

    public function getIdUser1()
    {
        return $this->idUser1;
    }

    public function setIdUser2($id)
    {
        $this->idUser2 = $id;

        return $this;
    }

    public function getIdUser2()
    {
        return $this->idUser2;
    }

}
