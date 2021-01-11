<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 12:14 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="assignment")
 */
class Assignment
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="assignmentDriver")
     */
    private $drivers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Departure", mappedBy="assignment")
     */
    private $departures;



    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $destination;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $note;

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
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @param mixed $drivers
     */
    public function setDrivers($drivers): void
    {
        $this->drivers = $drivers;
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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }






}