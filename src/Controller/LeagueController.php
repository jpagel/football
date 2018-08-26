<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use App\Exception\NotFoundException;
use App\Util\EntityFinder;
use App\Util\EntityUpdater;
use App\Util\OutputFormatter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/v1")
 */
class LeagueController extends AbstractController
{
    /**
     * @Route("/league/delete/{slug}", name="league-delete", methods={"DELETE"})
     * 
     * @param string $slug 
     * @param ObjectManager $em 
     * 
     * @return JsonResponse
     */
    public function deleteLeague(string $slug, ObjectManager $em)
    {
        try{
            $league = EntityFinder::findBySlug($em, League::class, $slug);
        } catch (NotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        EntityUpdater::detachAllTeams($league);
        $em->remove($league);
        $em->flush();
        
        return new JsonResponse(
            [
                'status' => 'SUCCESS'
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/league/{slug}", name="league", methods={"GET"})
     * 
     * @param string $slug 
     * @param ObjectManager $em 
     * 
     * @return JsonResponse
     */
    public function index(string $slug, ObjectManager $em)
    {
        try{
            $league = EntityFinder::findBySlug($em, League::class, $slug);
        } catch (NotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        $teams = $em
            ->getRepository(Team::class)
            ->findByLeague($league);

        $leagueFormatter = new OutputFormatter($league);

        $formattedTeams = array_map(
            function ($team) {
                return (new OutputFormatter($team))->getOutput();
            },
            $teams
        );

        return new JsonResponse(
            [
                'league' => $leagueFormatter->getOutput(),
                'teams'  => $formattedTeams
            ]
        );
    }
}
