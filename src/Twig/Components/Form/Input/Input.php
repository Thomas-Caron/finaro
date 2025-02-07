<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Form\Input;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Input
{
    public string $containerClass = '';
    public ?string $label = null;
    public string $class = '';
    public string $type = 'text';
    public ?string $id = null;
    public string $inputClass = '';
    public string $name;
    public null|string|int|float $value = null;
    public bool $required = false;
    public bool $autofocus = false;
    public ?string $error = null;

    public function getError(): string
    {
        return str_replace(['<ul>', '</ul>', '<li>', '</li>'], '', $this->error);
    }
}