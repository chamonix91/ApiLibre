<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 3:00 PM
 */

namespace App\Controller\Api;

use App\Entity\Departure;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/departure")
 */
class ApiDepartureController  extends AbstractController
{
    ////////////////////////////////////////
    ///////////  ADD DEPARTURE  ////////////
    ////////////////////////////////////////

    /**
     * @Route( "/newdeparture", name="api_new_departure", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newDeparture(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {
        $departure = new Departure();

        $agent = $this->getUser();
        $company= $agent->getCompany();
        $destination = $agent->getAdress();
        $date = new \DateTime('now');
        $dateFormat = $date->format('d-m-Y');


        $departure->setDestination($destination);
        $departure->setDatedeparture($date);
        $departure->setAgent($agent);
        $departure->setCompany($company);
        $departure->setIsConformed(true);
        $departure->setDone(false);

        $em = $this->getDoctrine()->getManager();

        $em->persist($departure);
        $em->flush();



        return new JsonResponse(["success departure has been added!"], 200);


    }

    ////////////////////////////////////////////////
    ///////////  GEY MY ALL DEPARTURES  ////////////
    ////////////////////////////////////////////////

    /**
     * @Route( "/alldeparture", name="api_get_my_all_departures", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAllDeparture(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {
        $manager = $this->getUser();

        $company = $manager->getCompany()->getId();

        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findBy(array('company'=>$company));

        $data = array();

        foreach ($departures as $departure){

            $data[] = [
                "agent id" => $departure->getAgent()->getId(),
                "agent first name" => $departure->getAgent()->getFirstname(),
                "agent last name" => $departure->getAgent()->getlastname(),
                "agent tel" => $departure->getAgent()->getTel(),
                "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                "destination" =>$departure->getDestination(),
            ];

        }


        return new JsonResponse($data, 200);


    }

    ////////////////////////////////////////////////
    ///////////  GEY ALL AGENT DEPARTURES  /////////
    ////////////////////////////////////////////////

    /**
     * @Route( "/allagentdeparture", name="api_get_all_agent_departures", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAllAgentDeparture(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {
        $agent = $this->getUser();

        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findBy(array('agent'=>$agent));

        $data = array();

        foreach ($departures as $departure){

            $data[] = [
                "agent id" => $departure->getAgent()->getId(),
                "agent first name" => $departure->getAgent()->getFirstname(),
                "agent last name" => $departure->getAgent()->getlastname(),
                "agent tel" => $departure->getAgent()->getTel(),
                "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                "destination" =>$departure->getDestination(),
            ];

        }


        return new JsonResponse($data, 200);


    }


    //////////////////////////////////////////
    /////////// UNCONFIRM DEPARTURE  /////////
    //////////////////////////////////////////


    /**
     * @Route( "/unconfirmmydeparture", name="api_unconfirm_my_departure", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unconfirmMyDeparture(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {

        $agent = $this->getUser();
        $company= $agent->getCompany();
        $destination = $agent->getAdress();

        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findBy(array("agent"=> $agent));

        foreach ($departures as $departure){
            $mylastdeparture = $departure;
        }

        $mylastdeparture->setIsConformed(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($mylastdeparture);
        $em->flush();

        return new JsonResponse(["success departure has been updated!"], 200);


    }




}