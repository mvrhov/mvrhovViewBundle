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

namespace mvrhov\ViewBundle\View;

use mageekguy\atoum\asserters\boolean;
use Symfony\Component\HttpFoundation\Response;

class RouteView extends AbstractView
{
    /** @var string */
    private $route;
    /** @var array */
    private $parameters;
    /** @var boolean */
    private $absolute;

    public function __construct(
        string $route,
        array $parameters = [],
        boolean $absolute = true,
        int $statusCode = Response::HTTP_FOUND,
        array $headers = []
    ) {
        $this->route = $route;
        $this->parameters = $parameters;
        $this->absolute = $absolute;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isAbsolute(): bool
    {
        return $this->absolute;
    }

}
