<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('taille', [$this, 'getLength']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('addition', [$this, 'calculAdd']),
        ];
    }

    public function getLength(array $tableau)
    {
        return "Le tableau contient ".count($tableau)." articles";
    }

    public function calculAdd(int $chiffre1, int $chiffre2)
    {
        return $chiffre1 + $chiffre2;
    }
}
