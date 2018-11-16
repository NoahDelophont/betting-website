<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LastUpdated
 *
 * @ORM\Table(name="last_updated")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LastUpdatedRepository")
 */
class LastUpdated
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
     * @var string
     *
     * @ORM\Column(name="game", type="string", length=255)
     */
    private $game;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set game
     *
     * @param string $game
     *
     * @return LastUpdated
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return string
     */
    public function getGame()
    {
        return $this->game;
    }
}

