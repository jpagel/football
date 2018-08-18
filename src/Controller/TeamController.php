<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\TeamFactory;
use App\Util\EntityUpdater;
use App\Util\OutputFormatter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/team/{slug}", name="team", methods={"GET"})
     */
    public function index(string $slug, ObjectManager $em)
    {
        $teamList = $em
                    ->getRepository(Team::class)
                    ->findBySlug($slug);
        if(0 == count($teamList))
        {
            return new JsonResponse(
                [
                    'error' => sprintf('there is no team with slug %s', $slug)
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $teamFormatter = new OutputFormatter(array_shift($teamList));
        return new JsonResponse([
            'team' => $teamFormatter->getOutput()
        ]);
    }

    /**
     * @Route("/team/create", name="team-create", methods={"POST"})
     */
    public function createTeam(ObjectManager $em, Request $request)
    {
        $teamData = json_decode($request->getContent(), true);
        $team = TeamFactory::create($teamData, $em);
        try{
            $em->persist($team);
            $em->flush();
        } catch(\Exception $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return new JsonResponse(
            [
                'id' => $team->getId()
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @Route("/team/delete/{slug}", name="team-delete", methods={"POST"})
     */
    public function deleteTeam(string $slug, ObjectManager $em)
    {
        $teamList = $em
                    ->getRepository(Team::class)
                    ->findBySlug($slug);
        if(0 == count($teamList))
        {
            return new JsonResponse(
                [
                    'error' => sprintf('there is no team with slug %s', $slug)
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $em->remove(array_shift($teamList));
        $em->flush();
        return new JsonResponse([
                'status' => 'SUCCESS'
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/team/{slug}", name="team-update", methods={"POST"})
     */
    public function updateTeam(string $slug, ObjectManager $em, Request $request)
    {
        $teamList = $em
                    ->getRepository(Team::class)
                    ->findBySlug($slug);
        if(0 == count($teamList))
        {
            return new JsonResponse(
                [
                    'error' => sprintf('there is no team with slug %s', $slug)
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $team = array_shift($teamList);
        $update = json_decode($request->getContent(), true);
        $team = EntityUpdater::applyUpdateToTeam($team, $update);
        $em->persist($team);
        $em->flush();
        return new JsonResponse([
                'status' => 'SUCCESS'
            ],
            JsonResponse::HTTP_OK
        );
    }
}
