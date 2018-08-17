<?php
namespace App\Entity;

use App\Entity\League;
use App\Entity\Team;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFactory {
    public static function create(array $properties, ObjectManager $em): Team
    {
        $team = new Team();
        $team->setName($properties['name']);
        $team->setSlug($properties['slug']);
        if(array_key_exists('strip', $properties)) {
            $team->setStrip($properties['strip']);
        }
        if(array_key_exists('league', $properties)) {
            $leagueList = $em
                ->getRepository(League::class)
                ->findBySlug($properties['league']);
            if(0 < count($leagueList)) {
              $team->setLeague(array_shift($leagueList));
            }
        }
        return $team;
    }
}
