<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Layout\Header\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavbarApp
{
    public string $firstname;
    public string $lastname;
    public string $email;
}
