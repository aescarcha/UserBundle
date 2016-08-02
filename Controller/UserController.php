<?php

namespace Aescarcha\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Aescarcha\UserBundle\Entity\User;

/**
 * User controller.
 */
class UserController extends Controller
{
    public function loggedUserAction()
    {
        $response = new JsonResponse();
        if( $user = $this->getUser()){
            $responseArray = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'profilePicture' => $user->getProfilePicture(),
            ];
            $response->setData($responseArray);
        }
        return $response;
    }

    /**
     * Method to retrieve an user based on facebook id
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function facebookUserAction( $id )
    {
        $em = $this->getDoctrine()->getManager();
        $response = new JsonResponse();
        $responseArray = [];
        $id = explode(',', $id);
        if( $users = $em->getRepository('AescarchaUserBundle:User')->findByFacebookId( $id ) ){
            foreach ($users as $user) {
                $responseArray[$user->getFacebookId()] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'profilePicture' => $user->getProfilePicture(),
                'facebookId' => $user->getFacebookId(),
                ];
            }

        }
        $response->setData($responseArray);
        return $response;
    }

}
