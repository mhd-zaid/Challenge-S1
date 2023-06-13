<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminAccessSubscriber implements EventSubscriberInterface
{
    private $security;
    private $urlGenerator;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        $this->translator->setLocale('fr');

        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY') && !$this->security->getUser()->getIsValidated() && strpos($pathInfo, '/admin') === 0) {
            
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('front_verifEmail')));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}