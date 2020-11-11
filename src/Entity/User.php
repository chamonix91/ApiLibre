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
 * @ORM\Entity
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







}