<?php
namespace Aescarcha\UserBundle\Tests\Functional;
use Aescarcha\UserBundle\Tests\Controller\BaseTest;

class UserControllerTest extends BaseTest
{
    public function setUp(){
        parent::setUp();
        $this->repository = $this->doctrine->getRepository('AescarchaUserBundle:User');
    }

    public function testEditProfile()
    {
        $this->login();

        $crawler = $this->client->request('GET', '/profile/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Update')->form();
        $form->setValues([
                         'fos_user_profile_form[username]' => 'pedroLol',
                         'fos_user_profile_form[email]' => 'peter@lala.com',
                         ]);

        $crawler = $this->client->submit($form);

    }
}
