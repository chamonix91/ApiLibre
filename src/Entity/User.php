<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 10/22/2020
 * Time: 2:03 PM
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Nucleos\UserBundle\Model\User as BaseUser;



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string")
     */
    protected $adress;

    /**
     * @ORM\Column(type="string")
     */
    protected $tel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="users")
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="drivers")
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Assignment", inversedBy="drivers")
     */
    private $assignmentDriver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Assignment", inversedBy="agents")
     */
    private $assignmentAgent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Departure", mappedBy="agent")
     */
    private $departure;

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adress
     */
    public function setAdress($adress): void
    {
        $this->adress = $adress;
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
    public function getAssignmentDriver()
    {
        return $this->assignmentDriver;
    }

    /**
     * @param mixed $assignmentDriver
     */
    public function setAssignmentDriver($assignmentDriver): void
    {
        $this->assignmentDriver = $assignmentDriver;
    }

    /**
     * @return mixed
     */
    public function getAssignmentAgent()
    {
        return $this->assignmentAgent;
    }

    /**
     * @param mixed $assignmentAgent
     */
    public function setAssignmentAgent($assignmentAgent): void
    {
        $this->assignmentAgent = $assignmentAgent;
    }

    /**
     * @return mixed
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * @param mixed $departure
     */
    public function setDeparture($departure): void
    {
        $this->departure = $departure;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

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
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param mixed $vehicle
     */
    public function setVehicle($vehicle): void
    {
        $this->vehicle = $vehicle;
    }








}