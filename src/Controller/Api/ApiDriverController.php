<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 12:12 PM
 */

namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UserService;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/driver")
 */
class ApiDriverController extends AbstractController
{

    /**
     * @Route("/new", name="api_new_driver",  methods={"POST"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newDriver(Request $request, UserManagerInterface $userManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
            'address' => new Assert\Length(array('min' => 1)),
            'firstname' => new Assert\Length(array('min' => 1)),
            'lastname' => new Assert\Length(array('min' => 1)),
            'tel' => new Assert\Length(array('min' => 1)),
        ));
        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $address = $data['address'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $tel = $data['tel'];

        $manager = $this->getUser();
        $company = $manager->getCompany();

        $user = new User();
        $user->setUsername($username);
        $user->setplainPassword($password);
        $user->setEmail($email);
        $user->setEnabled(true);
        $user->setRoles(['ROLE_DRIVER']);
        $user->setAdress($address);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setTel($tel);
        $user->setSuperAdmin(false);
        $user->setCompany($company);
        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);
    }

}