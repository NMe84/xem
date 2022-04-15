<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig;

use Cocur\Slugify\Slugify;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class Extension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('slugify', function ($string) {
                return (new Slugify())->slugify($string);
            }),
        ];
    }

    public function getFunctions(): array
    {
        return [];
    }

    public function getName()
    {
        return 'xem_extension';
    }
}
