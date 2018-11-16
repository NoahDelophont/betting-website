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
     * @ORM\Column(name="data", type="string", length=255)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="utcDate", type="string", length=255)
     */
    private $utcDate;


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
     * Set data
     *
     * @param string $data
     *
     * @return LastUpdated
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set utcDate
     *
     * @param string $utcDate
     *
     * @return LastUpdated
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
}

