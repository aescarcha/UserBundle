<?php
namespace Aescarcha\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            /* User already exists, update */
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);

            if(!$previousUser->getLocale() && isset($response->getResponse()['locale'])){
                $previousUser->setLocale($response->getResponse()['locale']);
            }

            if(!$previousUser->getBirthday() && isset($response->getResponse()['birthday'])){
                $previousUser->setBirthday($response->getResponse()['birthday']);
            }
            
            if(!$previousUser->getCountry() && isset($response->getResponse()['location'])){
                $previousUser->setLocation($response->getResponse()['location']['name']);
            }

            $previousUser->setUsername($response->getNickname());
            $previousUser->setProfilePicture($response->getProfilePicture());
            $previousUser->setBirthday( new \DateTime($response->getResponse()['birthday']) );
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($response->getNickname());
            $user->setEmail($response->getEmail());
            $user->setPassword($username);
            if(isset($response->getResponse()['locale'])){
                $user->setLocale($response->getResponse()['locale']);
            }
            if(isset($response->getResponse()['birthday'])){
                $user->setBirthday( new \DateTime($response->getResponse()['birthday']) );
            }
            if(isset($response->getResponse()['location'])){
                $user->setLocation($response->getResponse()['location']['name']);
            }
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

}
