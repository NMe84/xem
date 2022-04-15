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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class RouteLocaleMissingSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RouterInterface $router,
        private string $defaultLocale,
    ) {
    }

    public static function getSubscribedEvents(): iterable
    {
        yield KernelEvents::EXCEPTION => [['onKernelException', 17]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->isMainRequest() && $event->getThrowable() instanceof NotFoundHttpException) {
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
