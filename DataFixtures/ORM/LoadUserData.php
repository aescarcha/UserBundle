<?php
namespace Aescarcha\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aescarcha\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    protected $data = [
        0 => [
           'Username' => 'Alvaro',
           'UsernameCanonical' => 'alvaro',
           'Email' => 'aasss@msn.com',
           'EmailCanonical' => 'aasss@msn.com',
           'Enabled' => '1',
           'PlainPassword' => '1231251265',
           'Locked' => '0',
           'Expired' => '0',
           'Roles' => ['ROLE_USER'],
           'CredentialsExpired' => '0',
           'FacebookId' => '123124124124',
           'locale' => 'en_En',
           'language' => 'en',
           'FacebookAccessToken' => 'ASFASFASFASFASFASFASFXXXXASDAFASf',
        ],
    ];
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach($this->data as $userData){
            $user = new User();
            foreach($userData as $field => $value){
                $setter = "set$field";
                $user->$setter($value);
            }
            $manager->persist($user);
            $manager->flush();
        }

    }
}
