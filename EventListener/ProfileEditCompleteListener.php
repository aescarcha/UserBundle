<?php

namespace Aescarcha\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the user profile edit, and change locale
 */
class ProfileEditCompleteListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::PROFILE_EDIT_COMPLETED => 'onProfileEditCompleted',
            FOSUserEvents::PROFILE_EDIT_SUCCESS => array('onFormSuccess',-10),                        
        );
    }

    /**
     * Change redirect
     * @param  FormEvent $event [description]
     */
    public function onFormSuccess(FormEvent $event)
    {
        $url = $this->router->generate('homepage');
        $event->setResponse(new RedirectResponse($url));
    }

    /**
     * Change locale
     * @param  FilterUserResponseEvent $event 
     */
    public function onProfileEditCompleted(FilterUserResponseEvent $event)
    {
        $event->getRequest()->setLocale($event->getUser()->getLanguage());
        $event->getRequest()->getSession()->set('_locale', $event->getUser()->getLanguage());
    }
}