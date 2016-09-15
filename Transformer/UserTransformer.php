<?php
namespace Aescarcha\UserBundle\Transformer;

use Aescarcha\UserTransformer\Entity\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{

    public function transform( User $entity )
    {
        return [
            'id'      => $entity->getId(),
            'birthday'   => $entity->getBirthday(),
            'locale'   => $entity->getLocale(),
            'language'   => $entity->getLanguage(),
            'country'   => $entity->getCountry(),
            'region'   => $entity->getRegion(),
            'city'   => $entity->getCity(),
            'bio'   => $entity->getBio(),
            'name'   => $entity->getName(),
            'profilePicture'   => $entity->getProfilePicture(),
            'links'   => [
                'self' => [
                    'rel' => 'self',
                    'uri' => '/users/'.$entity->getId(),
                ],
            ],
        ];
    }

}


