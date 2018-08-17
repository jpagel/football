<?php
namespace App\DataFixtures;

use App\Entity\League;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $league = new League();
        $leagueName = 'Premier League';
        $league->setName($leagueName);
        $league->setSlug($this->sluggify($leagueName));
        $manager->persist($league);
        $manager->flush();
        for($i = 0; $i < 5; $i++) {
            $team = new Team();
            $name = sprintf('%s United', chr(65 + $i));
            $slug = $this->sluggify($name);
            $team->setName($name);
            $team->setSlug($slug);
            $team->setStrip(sprintf('strip_for_%s', $slug));
            $team->setLeague($league);
            $manager->persist($team);
        }
        $manager->flush();
    }

    protected function sluggify(string $s): string
    {
        return str_replace([' ', ',', '!', '?', '&'], '_', strtolower($s));
    }
}
