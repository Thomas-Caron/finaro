<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Layout\Header\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class UserNav
{
    public string $firstname;
    public string $lastname;
}