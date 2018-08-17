<?php
namespace App\Util;

use App\Entity\League;
use App\Entity\Team;

class OutputFormatter 
{
    public function __construct($formattableInstance)
    {
        $this->instance = $formattableInstance;
        $this->formattableClass = get_class($formattableInstance);
    }

    public function getOutput(): array
    {
        switch($this->formattableClass) {
            case League::class: return $this->formatLeague($this->instance);
            case Team::class:   return $this->formatTeam($this->instance);
            default: throw new \Exception(sprintf('OutputFormatter cannot format class %s', $this->formattableClass));
        }
    }

    public function formatLeague(League $league): array
    {
        return [
            'id'   => $league->getId(),
            'name' => $league->getName(),
            'slug' => $league->getSlug()
        ];
    }

    public function formatTeam(Team $team): array
    {
        return [
            'id'    => $team->getId(),
            'name'  => $team->getName(),
            'slug'  => $team->getSlug(),
            'strip' => $team->getStrip()
        ];
    }
}
