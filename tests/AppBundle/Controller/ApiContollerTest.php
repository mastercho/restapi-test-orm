<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\Api\v1;

class ApiControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/v1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Please enter your API function!', $crawler->filter('body')->text());
    }

    public function testGetTeam() {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', '127.0.0.1:8000');

        $crawler = $client->request('GET', '/api/v1/getTeams/Championship');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Championship', $client->getResponse()->getContent());
    }

    public function testCreateTeam() {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', '127.0.0.1:8000');

        $crawler = $client->request('POST', '/api/v1/createTeam?team=Aydan&league=Worst League', array('team' => 'Aydan', 'league' => 'Worst League'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Team was added', $crawler->filter('body')->text());
    }

    public function testUpdateTeam() {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', '127.0.0.1:8000');

        $crawler = $client->request('PUT', '/api/v1/updateTeam/1/?name=What&strip=New League2');

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testdeleteLeague() {
        $client = static::createClient();
        $client->setServerParameter('HTTP_HOST', '127.0.0.1:8000');

        $crawler = $client->request('DELETE', '/api/v1/deleteLeague/test');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('deleted successfully', $client->getResponse()->getContent());
    }

}
