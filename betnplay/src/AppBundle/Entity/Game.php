<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @ORM\Column(name="api_id", type="integer", unique=true)
     */
    private $apiId;

    /**
     * @var string
     *
     * @ORM\Column(name="competition", type="string", length=500)
     */
    private $competition;

    /**
     * @var string
     *
     * @ORM\Column(name="utcDate", type="string", length=255)
     */
    private $utcDate;

    /**
     * @var int
     *
     * @ORM\Column(name="matchDay", type="integer")
     */
    private $matchDay;

    /**
     * @var string
     *
     * @ORM\Column(name="homeTeam", type="string", length=255)
     */
    private $homeTeam;

    /**
     * @var string
     *
     * @ORM\Column(name="awayTeam", type="string", length=255)
     */
    private $awayTeam;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=700)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="cote", type="string", length=255)
     */
    private $cote;


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
     * Set apiId
     *
     * @param integer $apiId
     *
     * @return Game
     */
    public function setApiId($apiId)
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * Get apiId
     *
     * @return int
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * Set competition
     *
     * @param string $competition
     *
     * @return Game
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;

        return $this;
    }

    /**
     * Get competition
     *
     * @return string
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set utcDate
     *
     * @param string $utcDate
     *
     * @return Game
     */
    public function setUtcDate($utcDate)
    {
        $this->utcDate = $utcDate;

        return $this;
    }

    /**
     * Get utcDate
     *
     * @return string
     */
    public function getUtcDate()
    {
        return $this->utcDate;
    }

    /**
     * Set matchDay
     *
     * @param integer $matchDay
     *
     * @return Game
     */
    public function setMatchDay($matchDay)
    {
        $this->matchDay = $matchDay;

        return $this;
    }

    /**
     * Get matchDay
     *
     * @return int
     */
    public function getMatchDay()
    {
        return $this->matchDay;
    }

    /**
     * Set homeTeam
     *
     * @param string $homeTeam
     *
     * @return Game
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return string
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param string $awayTeam
     *
     * @return Game
     */
    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return string
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return Game
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set cote
     *
     * @param string $cote
     *
     * @return Game
     */
    public function setCote($cote)
    {
        $this->cote = $cote;

        return $this;
    }

    /**
     * Get cote
     *
     * @return string
     */
    public function getCote()
    {
        return $this->cote;
    }
}

