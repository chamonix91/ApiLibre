<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/11/2020
 * Time: 6:11 PM
 */

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{

    //////////////////////////////////////////////
    ///////////  GET CURRENT USER  ///////////////
    //////////////////////////////////////////////

    /**
     * @Route("/current", name="api_current_user",  methods={"GET"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getCurrentUser()
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');



        $current  = $this->getUser();

        $data = [
            'id' => $current->getId(),
            'firstName' => $current->getFirstName(),
            'lastName' => $current->getLastName(),
            'email' => $current->getEmail(),
            'username' => $current->getUsername(),
            'role'=> $current->getRoles(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);


    }
}