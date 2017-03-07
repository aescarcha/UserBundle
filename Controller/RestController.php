<?php

namespace Aescarcha\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Serializer\ArraySerializer;

use Aescarcha\UserBundle\Transformer\UserTransformer;

use Aescarcha\UserBundle\Entity\User;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\Get;
/**
 * Rest User controller.
 */
class RestController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Finds an displays a Business entity",
     *  output="Aescarcha\BusinessBundle\Entity\Business",
     *  requirements={
     *      {"name"="entity", "dataType"="uuid", "description"="Unique id of the business entity"}
     *  },
     *  statusCodes={
     *         200="Returned when entity exists",
     *         404="Returned when entity is not found",
     *     }
     * )
     * 
     * @Get("/users/logged")
     */
    public function getLoggedUserAction(Request $request)
    {
        $fractal = new Manager();

        $entity = $this->get('security.token_storage')->getToken()->getUser();

        $resource = new Item($entity, new UserTransformer);
        $view = $this->view($fractal->createData($resource)->toArray(), 200);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Finds an displays a Business entity",
     *  output="Aescarcha\BusinessBundle\Entity\Business",
     *  requirements={
     *      {"name"="entity", "dataType"="uuid", "description"="Unique id of the business entity"}
     *  },
     *  statusCodes={
     *         200="Returned when entity exists",
     *         404="Returned when entity is not found",
     *     }
     * )
     */
    public function getUserAction(Request $request, User $entity)
    {
        $fractal = new Manager();

        $loggedUser = $this->get('security.token_storage')->getToken()->getUser();
        if($entity->getId() !== $loggedUser->getId()){
            throw $this->createAccessDeniedException( "You can't acces this route." );
        }
        
        $resource = new Item($entity, new UserTransformer);
        $view = $this->view($fractal->createData($resource)->toArray(), 200);
        return $this->handleView($view);
    }
}
