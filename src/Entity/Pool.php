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
 * @ORM\Entity
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
     * @ORM\OneToMany(targetEntity="App\Entity\Departure", mappedBy="pool")
     */
    private $departures;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="pools")
     */
    private $company;

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

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
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





}