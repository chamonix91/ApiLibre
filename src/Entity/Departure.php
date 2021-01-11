<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 2:03 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartureRepository")
 * @ORM\Table(name="departure")
 */
class Departure
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
    private $datedeparture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isConfirmed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="departure")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Assignment", inversedBy="departures")
     */
    private $assignment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pool", inversedBy="departures")
     */
    private $pool;

    /**
     * @ORM\Column(type="string")
     */
    protected $destination;

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
    public function getDatedeparture()
    {
        return $this->datedeparture;
    }

    /**
     * @param mixed $datedeparture
     */
    public function setDatedeparture($datedeparture): void
    {
        $this->datedeparture = $datedeparture;
    }

    /**
     * @return mixed
     */
    public function getisConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @param mixed $isConfirmed
     */
    public function setIsConfirmed($isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * @param mixed $done
     */
    public function setDone($done): void
    {
        $this->done = $done;
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent): void
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * @param mixed $assignment
     */
    public function setAssignment($assignment): void
    {
        $this->assignment = $assignment;
    }

    /**
     * @return mixed
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @param mixed $pool
     */
    public function setPool($pool): void
    {
        $this->pool = $pool;
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



}