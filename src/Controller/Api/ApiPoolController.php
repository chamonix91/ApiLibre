<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 3:46 PM
 */

namespace App\Controller\Api;


use App\Entity\Departure;
use App\Entity\Pool;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/pool")
 */
class ApiPoolController extends AbstractController
{
    ///////////////////////////////////
    ///////////  ADD POOL  ////////////
    ///////////////////////////////////

    /**
     * @Route( "/newpool", name="api_new_pool", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newPool(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {
        $em = $this->getDoctrine()->getManager();

        $today = new \DateTime("now");
        $date_format = $today->format('d-m-Y');
        $manager = $this->getUser();
        $company = $manager->getCompany();
        $idcompany = $company->getId();

        $pool = new Pool();
        $pool->setDatepool($today);
        $pool->setCompany($company);
        $pool->setConfirmed(false);
        $pool->setAffected(false);
        $em->persist($pool);
        $em->flush();


        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findByCompany(array($date_format ,$idcompany));


        $data = array();
        foreach ($departures as $departure){
            $date_departure = $departure->getDatedeparture();

            if ($date_format == $date_departure->format('d-m-Y')){

            $data[] = [
                'departure' => $departure->getId(),
            ];



            }


        }


        return new JsonResponse($data, 200);

    }




}