<?php

namespace App\EventSubscriber;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
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
    private $em;
    public function __construct(EntityManagerInterface $em,Security $security, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->em = $em;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();
        $company = $this->em->getRepository(Company::class)->findOneBy([
            'id' => 1
        ]);
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY') ) {
            $user = $this->security->getUser();
            $this->translator->setLocale($user->getLanguage());
        }
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