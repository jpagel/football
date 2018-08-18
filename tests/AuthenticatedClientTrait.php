<?php
namespace App\Tests;

use GuzzleHttp\Client;

trait AuthenticatedClientTrait
{
        protected function setAuthenticatedClient() {
            $client = new Client([
                'base_uri' => 'http://localhost:8000',
                'timeout'  => 2.0
            ]);
            $response = $client->post('/token');
            $token = json_decode($response->getBody())->token;
            $this->authenticatedClient = new Client([
                'base_uri' => 'http://localhost:8000',
                'timeout'  => 2.0,
                'headers' => ['Authorization' => sprintf('Bearer %s', $token)]
            ]);
        }
}
