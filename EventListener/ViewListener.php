<?php declare(strict_types=1);
/**
 * Released under the MIT License.
 *
 * Copyright (c) 2017 Miha Vrhovnik <miha.vrhovnik@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace mvrhov\ViewBundle\EventListener;

use mvrhov\ViewBundle\View\ResponderInterface;
use mvrhov\ViewBundle\View\RouteView;
use mvrhov\ViewBundle\View\TemplateView;
use mvrhov\ViewBundle\View\ViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ViewListener implements EventSubscriberInterface
{
    /** @var Environment */
    private $twig;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if ($result instanceof ResponderInterface) {
            $result = $result->getView($event->getRequest(), $event->getRequestType());
        } elseif (!($result instanceof ViewInterface)) {
            return;
        }

        $response = $result->getResponse();

        if ($result instanceof TemplateView) {
            $response->setContent($this->twig->render($result->getTemplateName(), $result->getData()));
        } elseif ($result instanceof RouteView) {
            $response->headers->set(
                'Location',
                $this->urlGenerator->generate(
                    $result->getRoute(),
                    $result->getParameters(),
                    $result->isAbsoluteUrl() ?
                        $this->urlGenerator::ABSOLUTE_URL : $this->urlGenerator::ABSOLUTE_PATH
                )
            );
        }

        $event->setResponse($response);
    }
}
