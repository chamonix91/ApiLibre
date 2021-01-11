<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 2:04 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoolRepository")
 * @ORM\Table(name="pool")
 */
class Pool
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $datepool;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $affected;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Departure", mappedBy="pool")
     */
    private $departures;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDatepool()
    {
        return $this->datepool;
    }

    /**
     * @param mixed $datepool
     */
    public function setDatepool($datepool): void
    {
        $this->datepool = $datepool;
    }

    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed($confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return mixed
     */
    public function getAffected()
    {
        return $this->affected;
    }

    /**
     * @param mixed $affected
     */
    public function setAffected($affected): void
    {
        $this->affected = $affected;
    }

    /**
     * @return mixed
     */
    public function getDepartures()
    {
        return $this->departures;
    }

    /**
     * @param mixed $departures
     */
    public function setDepartures($departures): void
    {
        $this->departures = $departures;
    }


}