<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Alert;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Alert
{
    public string $type = 'success';
    public string $class = '';
    public string $text;

    public function getIcon(): string
    {
        return match ($this->type) {
            'success' => 'bi:check-circle',
            'error' => 'bi:exclamation-circle',
        };
    }
}