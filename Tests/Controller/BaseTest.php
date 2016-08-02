<?php

namespace Aescarcha\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\BrowserKit\Cookie;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Symfony\Component\Console\Tester\CommandTester;

class BaseTest extends WebTestCase
{
    protected static $application;
    protected $client = null;
    protected $doctrine = null;
    protected $repository;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient();
        $this->doctrine = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->fixturize( $this->doctrine );
    }

    protected function fixturize( $doctrine )
    {
        $this->truncateAllTables( $doctrine );
        $fixtures = [
            new \Aescarcha\UserBundle\DataFixtures\ORM\LoadUserData(),
            new \Aescarcha\ChallengeBundle\DataFixtures\ORM\LoadPostData(),
        ];
        foreach($fixtures as $fixture){
            if($fixture instanceof \Symfony\Component\DependencyInjection\ContainerAwareInterface){
                $fixture->setContainer( $this->client->getContainer() );
            }
            $fixture->load($doctrine);
        }
    }

    protected function truncateAllTables( $entityManager )
    {
        $connection = $entityManager->getConnection();
        $connection->executeUpdate("SET foreign_key_checks = 0;");

        $schemaManager = $connection->getSchemaManager();
        $tables = $schemaManager->listTables();
        $query = '';
        foreach($tables as $table) {
            $name = $table->getName();
            $query .= 'TRUNCATE ' . $name . ';';
        }
        $connection->executeQuery($query, array(), array());
        $connection->executeUpdate("SET foreign_key_checks = 1;");
    }

    protected function login( $userName = 'Alvaro')
    {
        $session = $this->client->getContainer()->get('session');
        $container = $this->client->getContainer();
        $userManager = $container->get('fos_user.user_manager');
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name'); 
        $user = $userManager->findUserBy(array('username' => $userName));
        $loginManager->loginUser($firewallName, $user);
        $container->get('session')->set('_security_' . $firewallName,
                                        serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
    }

    /**
     * Test to remove phpunit warnings
     * @return [type] [description]
     *
     */
    public function testNothing()
    {
        $this->assertEquals(1, 1);
    }

}
