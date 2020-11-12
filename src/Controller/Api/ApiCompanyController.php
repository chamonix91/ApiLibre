<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/11/2020
 * Time: 4:40 PM
 */

namespace App\Controller\Api;


use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Company;


/**
 * @Route("/company")
 */
class ApiCompanyController extends AbstractController
{

    //////////////////////////////////////
    ///////////  ADD COMPANY  ////////////
    //////////////////////////////////////

    /**
     * @Route( "/new", name="api_new_company", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newCompany (SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {

        $em = $this->getDoctrine()->getManager();
        $data = json_decode(
            $request->getContent(),
            true
        );

        $name = $data['name'];
        $withmanager = $data['withmanager'];

        $company = new Company();

        if ($withmanager == "0"){

            $company->setName($name);

            dump($company);die();
            $em->persist($company);
            $em->flush();
        }

        if ($withmanager == "1"){

            $company->setName($name);

            $manager = new User();

            $firstname = $data['firstname'];
            $username = $data['username'];
            $password = $data['password'];
            $email = $data['email'];

            $manager->setFirstname($firstname);
            $manager->setUsername($username);
            $manager->setEmail($email);
            $manager->setPlainPassword($password);
            $manager->setRoles(['ROLE_MANAGER']);
            $manager->setEnabled(true);

            $em->persist($manager);
            $em->flush();

            $em->persist($company);
            $em->flush();



        }

        return new JsonResponse(["success" => $company->setName() . " has been registered!"], 200);
    }

}