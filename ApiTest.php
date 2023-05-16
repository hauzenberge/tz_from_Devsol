<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class ApiTest extends PHPUnit\Framework\TestCase
{
    private $client;


    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://work/index.php',
            'http_errors' => false,
        ]);

        $this->token = 'my_secret_token';
    }

    public function testGetItems()
    {
        $response = $this->client->get('/items', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);

        $this->assertEquals('John Doe', $data['name']);
        $this->assertEquals('john@example.com', $data['email']);
    }
}
