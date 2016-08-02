<?php

namespace Aescarcha\UserBundle\Tests\Entity;

use Aescarcha\UserBundle\Tests\Controller\BaseTest;

class UserTest extends BaseTest
{
    public function setUp(){
        parent::setUp();
        $this->repository = $this->doctrine->getRepository('AescarchaUserBundle:User');
    }

    /**
     *
     */
    public function testGetProfileImage()
    {
        $this->login();
        $user = $this->repository->find(1);
        $this->assertEquals( '//graph.facebook.com/4124124124/picture', $user->getProfilePicture() );
        $this->assertEquals( '//graph.facebook.com/4124124124/picture?type=large', $user->getProfilePicture('large') );

    }
}
