<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Button;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $type = 'button';
    public string $class = '';
    public string $color = 'dark';
    public string $text;
}