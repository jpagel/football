<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use App\Util\OutputFormatter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LeagueController extends AbstractController
{
    /**
     * @Route("/league/delete/{slug}", name="league-delete", methods={"POST"})
     */
    public function deleteLeague(string $slug, ObjectManager $em)
    {
        $leagueRepository = $em->getRepository(League::class);
        $leagueList = $leagueRepository
            ->findBySlug($slug);
        if(0 == count($leagueList)) {
            $errorResponse = new JsonResponse(
                [
                    'error' => sprintf('there is no league with slug %s', $slug)
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
            return $errorResponse;
        }

        $league = array_shift($leagueList);

        //@todo write a Util method for clearing the teams
        $league->getTeams()->map(
            function(Team $team) {
                $team->setLeague(null);
            }
        );

        $em->remove($league);
        $em->flush();
        
        return new JsonResponse([
            'status' => 'SUCCESS'
        ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/league/{slug}", name="league", methods={"GET"})
     */
    public function index(string $slug, ObjectManager $em)
    {
        $leagueList = $em
            ->getRepository(League::class)
            ->findBySlug($slug);
        if(0 == count($leagueList)) {
            $errorResponse = new JsonResponse(
                [
                    'error' => sprintf('there is no league with slug %s', $slug)
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
            return $errorResponse;
        }

        $league = array_shift($leagueList);
        $teams = $em
            ->getRepository(Team::class)
            ->findByLeague($league);

        $leagueFormatter = new OutputFormatter($league);

        $formattedTeams = array_map(
            function($team) {
                return (new OutputFormatter($team))->getOutput();
            },
            $teams
        );

        return new JsonResponse(
            [
                'league' => $leagueFormatter->getOutput(),
                'teams' => $formattedTeams,
            ]
        );
    }
}
