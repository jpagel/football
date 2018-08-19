<?php
namespace App\Tests\Controller;

use App\Tests\AuthenticatedClientTrait;
use App\Util\FixturesTrait;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ServerHealthControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'timeout'  => 2.0
        ]);
    }

    public function testServerHealth()
    {
        $response = $this->client->get('/server-health');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', json_decode($response->getBody())->status);
    }
}
