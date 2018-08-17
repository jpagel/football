<?php
namespace App\Tests\Util;

use App\Entity\League;
use App\Entity\Team;
use App\Util\OutputFormatter;
use PHPUnit\Framework\TestCase;

class OutputFormatterTest extends TestCase
{
    public function testFormatLeague()
    {
        $league = $this->createMock(League::class);
        $league->method('getId')->willReturn(10);
        $league->method('getName')->willReturn('Premier League');
        $league->method('getSlug')->willReturn('premier_league');
        $formatter = new OutputFormatter($league);
        $this->assertArrayHasKey('id', $formatter->formatLeague($league));
        $this->assertArrayHasKey('name', $formatter->formatLeague($league));
        $this->assertArrayHasKey('slug', $formatter->formatLeague($league));
        $this->assertEquals(10, $formatter->formatLeague($league)['id']);
        $this->assertEquals('Premier League', $formatter->formatLeague($league)['name']);
        $this->assertEquals('premier_league', $formatter->formatLeague($league)['slug']);
    }

    public function testFormatTeam()
    {
        $team = $this->createMock(Team::class);
        $team->method('getId')->willReturn(20);
        $team->method('getName')->willReturn('Fullchester United');
        $team->method('getSlug')->willReturn('fullchester_united');
        $team->method('getStrip')->willReturn('green_strip');
        $formatter = new OutputFormatter($team);
        $this->assertArrayHasKey('id', $formatter->formatteam($team));
        $this->assertArrayHasKey('name', $formatter->formatTeam($team));
        $this->assertArrayHasKey('slug', $formatter->formatTeam($team));
        $this->assertArrayHasKey('strip', $formatter->formatTeam($team));
        $this->assertEquals(20, $formatter->formatTeam($team)['id']);
        $this->assertEquals('Fullchester United', $formatter->formatTeam($team)['name']);
        $this->assertEquals('fullchester_united', $formatter->formatTeam($team)['slug']);
        $this->assertEquals('green_strip', $formatter->formatTeam($team)['strip']);
    }
}
