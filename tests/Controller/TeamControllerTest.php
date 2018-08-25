<?php
namespace App\Tests\Controller;

use App\Tests\AuthenticatedClientTrait;
use App\Util\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

class TeamControllerTest extends WebTestCase
{
    use FixturesTrait, AuthenticatedClientTrait;
    
    protected function setUp()
    {
        $this->loadFixtures();
        $this->setAuthenticatedClient();
        parent::setUp();
    }

    public function testUnknownTeamResultsIn404()
    {
        $this->loadFixtures();
        $response = $this->authenticatedClient->get('/api/v1/team/no_such_team', ['http_errors' => false]);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testGetTeamDetails()
    {
        $response = $this->authenticatedClient->get('/api/v1/team/c_united');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('team', $data);
        $this->assertEquals('c_united', $data['team']['slug']);
    }

    public function testCreateTeam()
    {
        $teamData = [
            'name'  => 'Testington City',
            'slug'  => 'testington_city',
            'strip' => 'testington_strip'
        ];
        $response = $this->authenticatedClient->request('POST', '/api/v1/team/create', [ 'json' => $teamData]);
        $this->assertEquals(201, $response->getStatusCode());
        $teamResponse = $this->authenticatedClient->get('/api/v1/team/testington_city');
        $this->assertEquals(200, $teamResponse->getStatusCode());
        $this->assertEquals('Testington City', json_decode($teamResponse->getBody())->team->name);
    }

    public function testUpdateTeam()
    {
        $response = $this->authenticatedClient->request('POST', '/api/v1/team/c_united', [ 'json' => ['name' => 'Cambridge Ecumenicals', 'strip' => 'golden_brown']]);
        $this->assertEquals(200, $response->getStatusCode());
        $teamResponse = $this->authenticatedClient->get('/api/v1/team/c_united');
        $teamData = json_decode($teamResponse->getBody(), true);
        $this->assertEquals('Cambridge Ecumenicals', $teamData['team']['name']);
        $this->assertEquals('golden_brown', $teamData['team']['strip']);
    }

    public function testDeleteTeam()
    {
        $teamResponse1 = $this->authenticatedClient->get('/api/v1/team/c_united');
        $this->assertEquals(200, $teamResponse1->getStatusCode());
        $response = $this->authenticatedClient->request('POST', '/api/v1/team/delete/c_united');
        $this->assertEquals(200, $response->getStatusCode());
        $teamResponse2 = $this->authenticatedClient->get('/api/v1/team/c_united', ['http_errors' => false]);
        $this->assertEquals(404, $teamResponse2->getStatusCode());
    }
}
