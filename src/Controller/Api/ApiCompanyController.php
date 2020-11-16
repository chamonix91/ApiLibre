<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/11/2020
 * Time: 4:40 PM
 */

namespace App\Controller\Api;


use App\Service\UserService;
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
    public function newCompany(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {

        $em = $this->getDoctrine()->getManager();
        $data = json_decode(
            $request->getContent(),
            true
        );

        $name = $data['name'];
        $withmanager = $data['withmanager'];

        $company = new Company();

        if ($withmanager == "0") {

            $company->setName($name);

            $em->persist($company);
            $em->flush();
        }

        if ($withmanager == "1") {

            $company->setName($name);

            $em->persist($company);
            $em->flush();

            $manager = new User();
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $adress = $data['adress'];
            $username = $data['username'];
            $password = $data['password'];
            $email = $data['email'];

            $manager->setFirstname($firstname);
            $manager->setLastname($lastname);
            $manager->setAdress($adress);
            $manager->setUsername($username);
            $manager->setEmail($email);
            $manager->setPlainPassword($password);
            $manager->setRoles(['ROLE_MANAGER']);
            $manager->setEnabled(true);
            $manager->setCompany($company);

            $em->persist($manager);
            $em->flush();

        }

        return new JsonResponse(["success" => $company->getName() . " has been added!"], 200);
    }


    ////////////////////////////////////////
    ///////////  ALL COMPANIES  ////////////
    ////////////////////////////////////////


    /**
     * @Route( "/allcompanies", name="api_get_allcompanies", methods={"GET"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function GetAllCompanies(UserService $userService)
    {
        $companies = $this->getDoctrine()
            ->getRepository(Company::class)
            ->findAll();

        $data = array();

        foreach ($companies as $company) {
            $idcompany = $company->getId();

            $companyUsers = $this->getDoctrine()
                ->getRepository(User::class)->findBy(array('company' => $idcompany));
            $dataUsers = array();
            if ($companyUsers) {
                foreach ($companyUsers as $companyUser) {

                    $dataUsers = array();
                    if (in_array('ROLE_MANAGER',$companyUser->getRoles(), true)) {
                        $oneUser = $userService->GetOneUser($companyUser);
                        $dataUsers[] = $oneUser ;
                    }
                }
            }
            $data[] = [
                'id' => $company->getId(),
                'name' => $company->getName(),
                'manager' => $dataUsers,
            ];
        }

        return new JsonResponse($data, 200);
    }

    //////////////////////////////////////////////
    ///////////  GET COMPANY MANAGER  ////////////
    //////////////////////////////////////////////

    /**
     * @Route( "/getcompanaymanager/{id}", name="api_get_company_manager", methods={"GET"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function GetCompanyManagers($id, Request $request, UserService $userService){

        $data = array();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array('company'=>$id));

        foreach ($users as $user){

            if (in_array('ROLE_MANAGER',$user->getRoles(),true)){
                $oneUser = $userService->GetOneUser($user);
                $data[] = $oneUser ;
            }
        }

        return new JsonResponse($data, 200);

    }


    ////////////////////////////////////////////
    ///////////  GET COMPANY BY ID  ////////////
    ////////////////////////////////////////////

    /**
     * @Route( "/getcompanaybyid/{id}", name="api_get_company_by_id", methods={"GET"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function GetCompanyById($id, Request $request, UserService $userService){

        $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->find($id);

        $data = [
            'id'=>$company->getId() ,
            'name' => $company->getName(),
        ];

        return new JsonResponse($data, 200);

    }


    //////////////////////////////////////
    /////////  UPDATE COMPANY  ///////////
    //////////////////////////////////////

    /**
     * @Route( "/updatecompany/{id}", name="api_update_company", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateCompany(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode(
            $request->getContent(),
            true
        );

        $name = $data['name'];

        $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->find($id);

        if ($company){
            $company->setName($name);
        }


        $em->persist($company);
        $em->flush();

        return new JsonResponse(["success" => $company->getName() . " has been updated!"], 200);

    }








}