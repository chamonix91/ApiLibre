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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Entity\Company;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


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
        $address_company = $data['address_company'];
        $website = $data['website'];
        $tel_company = $data['tel_company'];
        $email_company = $data['email_company'];
        $sales_manager = $data['sales_manager'];
        $withmanager = $data['withmanager'];

        $company = new Company();

        if ($withmanager == "0") {

            $company->setName($name);
            $company->setAddress($address_company);
            $company->setWebsite($website);
            $company->setTel($tel_company);
            $company->setEmail($email_company);
            $company->setSalesManager($sales_manager);

            $em->persist($company);
            $em->flush();
        }

        if ($withmanager == "1") {

            $company->setName($name);
            $company->setAddress($address_company);
            $company->setWebsite($website);
            $company->setTel($tel_company);
            $company->setEmail($email_company);
            $company->setSalesManager($sales_manager);

            $em->persist($company);
            $em->flush();

            $manager = new User();
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $adress = $data['adress'];
            $username = $data['username'];
            $password = $data['password'];
            $email = $data['email'];
            $tel = $data['tel'];

            $manager->setFirstname($firstname);
            $manager->setLastname($lastname);
            $manager->setAdress($adress);
            $manager->setUsername($username);
            $manager->setEmail($email);
            $manager->setTel($tel);
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
            $dataUsers = array();
            $idcompany = $company->getId();

            $companyUsers = $this->getDoctrine()
                ->getRepository(User::class)->findBy(array('company' => $idcompany));
            $dataUsers = array();
            if ($companyUsers) {

                foreach ($companyUsers as $companyUser) {
                    if (in_array('ROLE_MANAGER',$companyUser->getRoles(), true)) {
                        $oneUser = $userService->GetOneUser($companyUser);
                        $dataUsers[] = $oneUser ;

                    }
                }
            }
            $data[] = [
                'id' => $company->getId(),
                'name' => $company->getName(),
                'address_company' =>$company->getAddress(),
                'website' =>$company->getWebsite(),
                'tel_company' =>$company->getTel(),
                'email_company' =>$company->getEmail(),
                'sales_manager' =>$company->getSalesManager(),
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
            'address_company' =>$company->getAddress(),
            'website' =>$company->getWebsite(),
            'tel_company' =>$company->getTel(),
            'email_company' =>$company->getEmail(),
            'sales_manager' =>$company->getSalesManager(),
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
        $address = $data['address_company'];
        $website = $data['website'];
        $tel = $data['tel_company'];
        $email = $data['email_company'];
        $sales_manager = $data['sales_manager'];

        $company = $this->getDoctrine()
            ->getRepository(Company::class)
            ->find($id);

        if ($company){
            $company->setName($name);
            $company->setAddress($address);
            $company->setWebsite($website);
            $company->setTel($tel);
            $company->setEmail($email);
            $company->setSalesManager($sales_manager);
        }


        $em->persist($company);
        $em->flush();

        return new JsonResponse(["success" => $company->getName() . " has been updated!"], 200);

    }








}