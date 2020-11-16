<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/13/2020
 * Time: 1:12 PM
 */

namespace App\Service;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;


class UserService
{

    //////////////////////////
    //////  GET ONE USER /////
    //////////////////////////

    /**
     * @param User $user
     * @return array
     */
    public function GetOneUser( User $user )
    {


        $formatted = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstname' => $user->getfirstname(),
            'lastname' => $user->getlastname(),
            'email' => $user->getEmail(),
            'address' => $user->getAdress(),
            'lastLogin' => $user->getlastLogin(),
            'role'=> $user->getRoles()
        ];




        return $formatted ;

    }

}