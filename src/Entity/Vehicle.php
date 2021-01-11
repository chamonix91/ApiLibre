<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 12:12 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="vehicle")
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="vehicle")
     */
    private $drivers;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
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





}