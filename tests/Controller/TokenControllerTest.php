<?php
namespace App\Tests\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'timeout'  => 2.0
        ]);

        parent::setUp();
    }
    
    public function testPostCreateToken()
    {
        $response = $this->client->post('/token');
        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('token', $data);
    }
}
