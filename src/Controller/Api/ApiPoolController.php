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
            ->findByCompany(array($date_format, $idcompany));


        $data = array();
        foreach ($departures as $departure) {
            $date_departure = $departure->getDatedeparture();

            if ($date_format == $date_departure->format('d-m-Y')) {

                $departure->setPool($pool);
                $em->persist($departure);
                $em->flush();

                $data[] = [
                    'departure' => $departure->getId(),
                ];
            }

        }

        return new JsonResponse($data, 200);

    }


    /////////////////////////////////////////
    ///////////  IS POOL CREATED ////////////
    /////////////////////////////////////////

    /**
     * @Route( "/iscreated", name="api_is_pool_created", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     */
    public function isCreatedPool(SerializerInterface $serializer, Request $request, UserManagerInterface $userManager)
    {

        $return = false;
        $company = $this->getUser()->getCompany();

        $pools = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->findBy(array('company' => $company));

        foreach ($pools as $pool) {
            $mylastpool = $pool;

        }


        if (!empty($mylastpool)){
            $date_pool = $mylastpool->getDatepool();
            $date_pool_format = $date_pool->format('y-m-d');
            $date = new \DateTime('now');
            $date_format = $date->format('y-m-d');

                if ($date_format == $date_pool_format) {

                    $return = true;

                }
        }

        return new JsonResponse(["iscreated" => $return], 200);
    }


    ////////////////////////////////////////
    ///////////  GET POOL BY ID ////////////
    ////////////////////////////////////////

    /**
     * @Route( "/pooldetail/{id}", name="api_pool_list", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function poolDetail(SerializerInterface $serializer, Request $request, $id)
    {


        $pool = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->find(array('id' => $id));

        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findBy(array('pool' => $id));

        foreach ($departures as $departure) {

            $data[] = [
                "agent_id" => $departure->getAgent()->getId(),
                "agent_firstname" => $departure->getAgent()->getFirstname(),
                "agent_lastname" => $departure->getAgent()->getlastname(),
                "agent_tel" => $departure->getAgent()->getTel(),
                "isconfirmed" => $departure->getIsConfirmed(),
                "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                "destination" => $departure->getDestination(),

            ];
        }

        return new JsonResponse($data, 200);
    }


    ///////////////////////////////////
    /////////  CONFIRM POOL  //////////
    ///////////////////////////////////

    /**
     * @Route( "/confirmpool/{id}", name="api_confirm_pool", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmPool(SerializerInterface $serializer, Request $request, $id)
    {
        $today = new \DateTime('now');
        $today_format = $today->format('y-m-d');
        $pool = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->find(array('id' => $id));

        $company = $pool->getCompany()->getId();

        $departures = $this->getDoctrine()
            ->getRepository(Departure::class)
            ->findBy(array("company" => $company));

        foreach ($departures as $departure) {
            $departureDate = $departure->getDatedeparture();
            $departureDate_format = $departureDate->format('y-m-d');
            if ($departureDate_format == $today_format && $departure->getIsConfirmed() == 1) {
                $departure->setPool($pool);
            }
        }


        $pool->setConfirmed(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($pool);
        $em->flush();

        return new JsonResponse(["success pool has been confirmed"], 200);
    }

    //////////////////////////////////////////
    ///////////  GET MY ALL POOLS ////////////
    //////////////////////////////////////////

    /**
     * @Route( "/myallpools", name="api_my_all_pools", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function myAllPools(SerializerInterface $serializer, Request $request)
    {


        $data = array();
        $manager = $this->getUser();
        $idcompany = $manager->getCompany();

        $pools = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->findBy(array("company" => $idcompany));

        foreach ($pools as $pool) {
            $idpool = $pool->getId();
            $departures = $this->getDoctrine()->getRepository(Departure::class)
                ->findBy(array('pool' => $idpool));

            $status = false;
            $today = new \DateTime('now');
            $today_format = $today->format('y-m-d');
            $poolDate = $pool->getDatepool();
            $poolDate_format = $poolDate->format('y-m-d');
            if ($poolDate_format == $today_format) {
                $status = true;
            } else {
                $status = false;
            }

            $agents = array();
            foreach ($departures as $departure) {
                $agents[] = [
                    "agent_id" => $departure->getAgent()->getId(),
                    "agent_firstname" => $departure->getAgent()->getFirstname(),
                    "agent_lastname" => $departure->getAgent()->getlastname(),
                    "agent_tel" => $departure->getAgent()->getTel(),
                    "isconfirmed" => $departure->getIsConfirmed(),
                    "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                    "destination" => $departure->getDestination(),
                ];
            }
            $data[] = [

                "id" => $pool->getId(),
                "date" => $pool->getDatepool()->format('d-m-Y H:i:s'),
                "confirmed" => $pool->getConfirmed(),
                "affected" => $pool->getAffected(),
                "istoday" => $status,
                "agents" => $agents
            ];


        }

        return new JsonResponse($data, 200);
    }

    /////////////////////////////////
    ///////////  IsToday ////////////
    /////////////////////////////////

    /**
     * @Route( "/istoday/{id}", name="api_is_today_pool", methods={"GET"})
     * @param Pool $pool
     * @return JsonResponse
     */
    public function istodayPool(Pool $pool)
    {
        $status = false;
        $today = new \DateTime('now');
        $today_format = $today->format('y-m-d');
        $poolDate = $pool->getDatepool();
        $poolDate_format = $poolDate->format('y-m-d');
        if ($poolDate_format == $today_format) {
            $status = true;
        } else {
            $status = false;
        }


        return new JsonResponse(['status' => $status], 200);
    }


    //////////////////////////////////////////
    /////////  GET CONFIRMED POOLS  //////////
    //////////////////////////////////////////

    /**
     * @Route( "/confirmedpools", name="api_get_confirmd_pools", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmedPools(SerializerInterface $serializer, Request $request)
    {
        $today = new \DateTime('now');
        $today_format = $today->format('y-m-d');
        $pools = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->findBy(array('confirmed' => "1"));

        foreach ($pools as $pool) {
            $idpool = $pool->getId();
            $poolDate = $pool->getDatepool();
            $poolDate_format = $poolDate->format('y-m-d');
            if ($poolDate_format == $today_format) {
                $departures = $this->getDoctrine()->getRepository(Departure::class)
                    ->findBy(array('pool' => $idpool));

                $agents = array();
                foreach ($departures as $departure) {
                    $agents[] = [
                        "agent_id" => $departure->getAgent()->getId(),
                        "agent_firstname" => $departure->getAgent()->getFirstname(),
                        "agent_lastname" => $departure->getAgent()->getlastname(),
                        "agent_tel" => $departure->getAgent()->getTel(),
                        "isconfirmed" => $departure->getIsConfirmed(),
                        "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                        "destination" => $departure->getDestination(),
                    ];
                }
                $data[] = [

                    "id" => $pool->getId(),
                    "date" => $pool->getDatepool()->format('d-m-Y H:i:s'),
                    "confirmed" => $pool->getConfirmed(),
                    "affected" => $pool->getAffected(),
                    "agents" => $agents
                ];
            }

        }

        return new JsonResponse($data, 200);
    }

    //////////////////////////////////////////
    ///////////  GET ALL POOLS ////////////
    //////////////////////////////////////////

    /**
     * @Route( "/allpools", name="api_all_pools", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function AllPools(SerializerInterface $serializer, Request $request)
    {


        $data = array();


        $pools = $this->getDoctrine()
            ->getRepository(Pool::class)
            ->findAll();

        foreach ($pools as $pool) {
            $idpool = $pool->getId();
            $departures = $this->getDoctrine()->getRepository(Departure::class)
                ->findBy(array('pool' => $idpool));

            $status = false;
            $today = new \DateTime('now');
            $today_format = $today->format('y-m-d');
            $poolDate = $pool->getDatepool();
            $poolDate_format = $poolDate->format('y-m-d');
            if ($poolDate_format == $today_format) {
                $status = true;
            } else {
                $status = false;
            }

            $agents = array();
            foreach ($departures as $departure) {
                $agents[] = [
                    "agent_id" => $departure->getAgent()->getId(),
                    "agent_firstname" => $departure->getAgent()->getFirstname(),
                    "agent_lastname" => $departure->getAgent()->getlastname(),
                    "agent_tel" => $departure->getAgent()->getTel(),
                    "isconfirmed" => $departure->getIsConfirmed(),
                    "date" => $departure->getDatedeparture()->format('d-m-Y H:i:s'),
                    "destination" => $departure->getDestination(),
                ];
            }
            $data[] = [

                "id" => $pool->getId(),
                "date" => $pool->getDatepool()->format('d-m-Y H:i:s'),
                "confirmed" => $pool->getConfirmed(),
                "affected" => $pool->getAffected(),
                "istoday" => $status,
                "agents" => $agents
            ];


        }

        return new JsonResponse($data, 200);
    }


}