<?php

namespace Aescarcha\UserBundle\Tests\Controller;

class RestControllerTest extends BaseTest
{
    public function setUp(){
        parent::setUp();
        $this->repository = $this->doctrine->getRepository('AescarchaUserBundle:User');
    }


    public function testGet()
    {
        $this->login();
        $id = $this->getOneEntity()->getId();

        $crawler = $this->client->request(
                                          'GET',
                                          '/users/' . $id,
                                          array(),
                                          array(),
                                          array('CONTENT_TYPE' => 'application/json'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals( 'Alvaro', $response['data']['name']);
        $this->assertEquals( '/users/' . $id, $response['data']['links']['self']['uri'] );
    }


    public function testGetLogged()
    {
        $this->login();
        $id = $this->getOneEntity()->getId();

        $crawler = $this->client->request(
                                          'GET',
                                          '/users/logged',
                                          array(),
                                          array(),
                                          array('CONTENT_TYPE' => 'application/json'));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals( 'Alvaro', $response['data']['name']);
        $this->assertEquals( '/users/' . $id, $response['data']['links']['self']['uri'] );

    }


    private function getOneEntity()
    {
        return $this->doctrine->getRepository('AescarchaUserBundle:User')->findAll()[0];
    }


}
