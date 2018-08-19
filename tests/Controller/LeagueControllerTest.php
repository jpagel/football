<?php
namespace App\Tests\Controller;

use App\Tests\AuthenticatedClientTrait;
use App\Util\FixturesTrait;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class LeagueControllerTest extends WebTestCase
{
    use FixturesTrait, AuthenticatedClientTrait;

    protected function setUp()
    {
        $this->setAuthenticatedClient();
        parent::setUp();
    }

    public function testTeamList()
    {
        $this->loadFixtures();
        $response = $this->authenticatedClient->get('/api/v1/league/premier_league');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('teams', $data);
        $this->assertCount(5, $data['teams']);
    }

    public function testLeagueDelete()
    {
        $this->loadFixtures();
        $response = $this->authenticatedClient->post('/api/v1/league/delete/premier_league');
        $this->assertEquals(200, $response->getStatusCode());
        $leagueResponse = $this->authenticatedClient->get('/api/v1/league/premier_league', ['http_errors' => false]);
        $this->assertEquals(404, $leagueResponse->getStatusCode());
    }
}
