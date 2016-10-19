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
        1 => [
           'Username' => 'Peter',
           'UsernameCanonical' => 'peter',
           'Email' => 'peter@msn.com',
           'EmailCanonical' => 'peter@msn.com',
           'Enabled' => '1',
           'PlainPassword' => '1231251265111',
           'Locked' => '0', 
           'Expired' => '0',
           'Roles' => ['ROLE_USER'],
           'CredentialsExpired' => '0',
           'FacebookId' => '12312412412411',
           'locale' => 'en_En',
           'language' => 'en',
           'FacebookAccessToken' => 'ASFAS1FASFASFASFASFASFXXXXASDAFASf',
        ],
        2 => [
           'Username' => 'Arthur',
           'UsernameCanonical' => 'arthur',
           'Email' => 'arthur@gmail.com',
           'EmailCanonical' => 'arthur@gmail.com',
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
