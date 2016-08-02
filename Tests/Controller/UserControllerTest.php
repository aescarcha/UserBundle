<?php

namespace Aescarcha\UserBundle\Tests\Controller;

class UserControllerTest extends BaseTest
{
    public function setUp(){
        parent::setUp();
        $this->repository = $this->doctrine->getRepository('AescarchaUserBundle:User');
    }

    /**
     *
     */
    public function testAjaxGetUser()
    {
        $this->login();

        $crawler = $this->client->request('GET', '/logged-user', array(), array(), array(
                                    'HTTP_X-Requested-With' => 'XMLHttpRequest',
                                    ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals( '1', $json->id );
        $this->assertEquals( 'Alvaro', $json->username );
    }

    /**
     *
     */
    public function testAjaxGetFacebookUser()
    {
        $this->login();

        $crawler = $this->client->request('GET', '/facebook-user/11231222', array(), array(), array(
                                    'HTTP_X-Requested-With' => 'XMLHttpRequest',
                                    ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals( '1', reset($json)->id );
        $this->assertEquals( 'Alvaro', reset($json)->username );
    }

    public function testAjaxGetFacebookUserSeveral()
    {
        $this->login();

        $crawler = $this->client->request('GET', '/facebook-user/123,51222', array(), array(), array(
                                    'HTTP_X-Requested-With' => 'XMLHttpRequest',
                                    ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $json = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals( '1', reset($json)->id );
        $this->assertEquals( 'Alvaro', reset($json)->username );
        $this->assertEquals( '2', end($json)->id );
        $this->assertEquals( 'Peter', end($json)->username );
    }

}
