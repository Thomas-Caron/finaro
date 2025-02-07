<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Layout\Header\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Navbar
{
    public bool $isConnected;
    public string $firstname;
    public string $lastname;
    public string $email;
}