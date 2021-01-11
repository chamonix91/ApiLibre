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
class ApiDepartureController extends AbstractController
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
        $agent = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $today = new \DateTime('today');
        $departure = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->agentTodayDeparture($today, $agent);

        if (!empty($departure)){

            $iddeparture = $departure[0]['id'];
            $mylastdeparture = $this->getDoctrine()
                ->getRepository(Departure::class)
                ->find($iddeparture);


            $mylastdeparture->setIsconfirmed(true);
            $em->persist($mylastdeparture);
            $em->flush();

        }
        else{
            $departure = new Departure();
            $destination = $agent->getAdress();
            $departure->setDestination($destination);
            $departure->setDatedeparture($today);
            $departure->setAgent($agent);
            $departure->setIsConfirmed(true);
            $departure->setDone(false);

            $em->persist($departure);
            $em->flush();
        }

        return new JsonResponse(["success departure is confirmed!"], 200);


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

        $today = new \DateTime('today');
        $departure = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->agentTodayDeparture($today, $agent);

        if (!empty($departure)){
            $iddeparture = $departure[0]['id'];

            $mylastdeparture = $this->getDoctrine()
                ->getRepository(Departure::class)
                ->find($iddeparture);

            $mylastdeparture->setIsConfirmed(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($mylastdeparture);
            $em->flush();
        }


        return new JsonResponse(["success departure is unconfirmed!"], 200);


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
            ->findAlldepartures($company);

        $data = array();
        foreach ($departures as $departure) {
            $iddeparute = $departure['id'];
            $mydeparture = $this->getDoctrine()
                ->getRepository(Departure::class)
                ->find($iddeparute);
            $data[] = [
                "agent_id" => $mydeparture->getAgent()->getId(),
                "agent_firstname" => $mydeparture->getAgent()->getFirstname(),
                "agent_lastname" => $mydeparture->getAgent()->getlastname(),
                "agent_tel" => $mydeparture->getAgent()->getTel(),
                "isconfirmed" => $mydeparture->getIsConfirmed(),
                "date" => $mydeparture->getDatedeparture()->format('d-m-Y H:i:s'),
                "destination" => $mydeparture->getDestination(),
                "done" => $mydeparture->getDone(),
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
            ->findBy(array('agent' => $agent));

        $data = array();

        foreach ($departures as $departure) {

            $data[] = [
                "agent_id" => $departure->getAgent()->getId(),
                "agent_firstname" => $departure->getAgent()->getFirstname(),
                "agent_lastname" => $departure->getAgent()->getlastname(),
                "agent_tel" => $departure->getAgent()->getTel(),
                "isconfirmed" => $departure->getIsConfirmed(),
                "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                "destination" => $departure->getDestination(),
                "done" => $departure->getDone(),
            ];

        }

        return new JsonResponse($data, 200);


    }



    //////////////////////////////////////////
    /////////// IS DEPARTURE CONFIRMED /////////
    //////////////////////////////////////////


    /**
     * @Route( "/isdepartureconfirmed", name="api_is_departure_confirmed", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function isDepartureConfirmed(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {
        $today = new \DateTime('today');

        $agent = $this->getUser();
        $departure = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->agentTodayDeparture($today, $agent);

        if (!empty($departure)) {
            $iddeparture = $departure[0]['id'];
            $mydeparture = $this->getDoctrine()
                ->getRepository(Departure::class)
                ->find($iddeparture);

            if ($mydeparture->getIsConfirmed() == true) {
                $status = true;
            } else {
                $status = false;
            }
        } else {

            $status = false;
        }

        return new JsonResponse(["Status_confirmed" => $status], 200);


    }


}