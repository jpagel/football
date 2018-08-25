<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\TeamFactory;
use App\Exception\NotFoundException;
use App\Util\EntityFinder;
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
     * 
     * @param string $slug
     * @param ObjectManager $em
     * 
     * @return JsonResponse
     */
    public function index(string $slug, ObjectManager $em)
    {
        try {
            $team = EntityFinder::findBySlug($em, Team::class, $slug);
        } catch (NotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $teamFormatter = new OutputFormatter($team);
        return new JsonResponse([
            'team' => $teamFormatter->getOutput()
        ]);
    }

    /**
     * @Route("/team/create", name="team-create", methods={"POST"})
     * 
     * @param ObjectManager $em 
     * @param Request $request 
     *
     * @return JsonResponse
     */
    public function createTeam(ObjectManager $em, Request $request)
    {
        $teamData = json_decode($request->getContent(), true);
        $team = TeamFactory::create($teamData, $em);
        try {
            $em->persist($team);
            $em->flush();
        } catch (\Exception $e) {
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
     * 
     * @param string $slug 
     * @param ObjectManager $em 
     *
     * @return JsonResponse
     */
    public function deleteTeam(string $slug, ObjectManager $em)
    {
        try {
            $team = EntityFinder::findBySlug($em, Team::class, $slug);
        } catch (NotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $em->remove($team);
        $em->flush();
        return new JsonResponse(
            [
                'status' => 'SUCCESS'
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/team/{slug}", name="team-update", methods={"POST"})
     * 
     * @param string $slug 
     * @param ObjectManager $em 
     * @param Request $request 
     * 
     * @return JsonResponse
     */
    public function updateTeam(string $slug, ObjectManager $em, Request $request)
    {
        try {
            $team = EntityFinder::findBySlug($em, Team::class, $slug);
        } catch (NotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $update = json_decode($request->getContent(), true);
        EntityUpdater::applyUpdateToTeam($team, $update);
        $em->persist($team);
        $em->flush();
        return new JsonResponse(
            [
                'status' => 'SUCCESS'
            ],
            JsonResponse::HTTP_OK
        );
    }
}
