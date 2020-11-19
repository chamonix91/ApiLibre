<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 11/16/2020
 * Time: 2:08 PM
 */

namespace App\Controller\Api;


use App\Entity\Assignment;
use App\Entity\Departure;
use App\Entity\Pool;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Nucleos\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/assignment")
 */
class ApiAssignmentController extends AbstractController
{

    ////////////////////////////////////////////
    ///////////  CREATE ASSIGNMENT  ////////////
    ////////////////////////////////////////////


    /**
     * @Route( "/new", name="api_new_assignment", methods={"POST"})
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAssignment(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $data = json_decode(
            $request->getContent(),
            true
        );

        $iddriver = $data['id'];
        $departures = $data['departures'];
        $destination = $data['destination'];
        $note = $data['note'];

        $count_depature = count($departures);

        $assignment = new Assignment();
        $assignment->setDate(new \DateTime('now'));
        $assignment->setDestination($destination);
        $assignment->setNote($note);
        $em->persist($assignment);
        $em->flush();

        for ($i=0; $i < $count_depature; $i++){

            $iddeparture = $departures[$i];
            $departure = $this->getDoctrine()
                ->getRepository(Departure::class)
                ->find($iddeparture);

            $idpool = $departure->getPool()->getId();
            $pool = $this->getDoctrine()
                ->getRepository(Pool::class)
                ->find($idpool);

            $driver = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($iddriver);

            $driver->setDriveraffected(true);
            $departure->setAssignment($assignment);
            $pool->setAffected(true);

            $em->persist($driver);
            $em->persist($departure);
            $em->persist($pool);
            $em->flush();
        }


        return new JsonResponse(["success" => $assignment->getId() . " has been added!"], 200);

    }

}