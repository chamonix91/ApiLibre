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
 * @ORM\Entity
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
    private $isConformed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $done;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="departure")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="departure")
     */
    private $company;

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
    public function getisConformed()
    {
        return $this->isConformed;
    }

    /**
     * @param mixed $isConformed
     */
    public function setIsConformed($isConformed): void
    {
        $this->isConformed = $isConformed;
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



}