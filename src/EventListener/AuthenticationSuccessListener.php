<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/17/2020
 * Time: 9:04 PM
 */

namespace App\EventListener;

use App\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthenticationSuccessListener
{

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }



        $data['data'] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstname' => $user->getfirstname(),
            'lastname' => $user->getlastname(),
            'email' => $user->getEmail(),
            'address' => $user->getAdress(),
            'lastLogin' => $user->getlastLogin(),
            'role'=> $user->getRoles(),
            'tel'=>$user->getTel(),
            'company' => $user->getCompany()->getId(),
        );

        $event->setData($data);
    }


}