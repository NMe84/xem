<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class RouteLocaleMissingSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;
    private RouterInterface $router;
    private string $defaultLocale;
    private array $allowedLocales;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, string $defaultLocale, array $allowedLocales)
    {
        $this->em = $em;
        $this->router = $router;
        $this->defaultLocale = $defaultLocale;
        $this->allowedLocales = $allowedLocales;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['onKernelException', 17]]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->isMasterRequest() && $event->getThrowable() instanceof NotFoundHttpException) {
            $locale = mb_substr($event->getRequest()->getPreferredLanguage(), 0, 2);
            try {
                if ($this->router->match("/{$locale}{$event->getRequest()->getPathInfo()}")) {
                    $event->setResponse(new RedirectResponse("/{$locale}{$event->getRequest()->getRequestUri()}"));
                }
            } catch (ResourceNotFoundException $e) {
                if ($locale != $this->defaultLocale) {
                    try {
                        $parameters = $this->router->match("/{$this->defaultLocale}{$event->getRequest()->getPathInfo()}");
                        $event->setResponse(new RedirectResponse($this->router->generate($parameters['_route'], ['_locale' => $parameters['_locale']])));
                    } catch (ResourceNotFoundException $e) {
                        // If we can't find a resource now, the 404 didn't happen because the locale was missing so keep going
                    }
                }
            }
        }
    }
}
