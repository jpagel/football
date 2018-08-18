<?php
namespace App\Util;

use App\Entity\Team;

class EntityUpdater
{
    /**
     * @param Team $team
     * @param array $update
     * 
     * @return Team
    */
    public static function applyUpdateToTeam(Team $team, array $update): Team
    {
        if (array_key_exists('name', $update)) {
            $team->setName($update['name']);
        }
        if (array_key_exists('slug', $update)) {
            $team->setSlug($update['slug']);
        }
        if (array_key_exists('strip', $update)) {
            $team->setStrip($update['strip']);
        }
        return $team;
    }
}
