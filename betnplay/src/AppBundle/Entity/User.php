<?php
/**
 * Created by PhpStorm.
 * User: Noah
 * Date: 10/10/2018
 * Time: 16:59
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="level",type="integer")
     */
    private $level;


    public function getId()
    {
        return $this->id;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }
}