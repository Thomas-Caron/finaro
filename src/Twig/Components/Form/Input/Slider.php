<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Form\Input;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Slider extends Input
{
    public ?float $min = null;
    public ?float $max = null;
    public ?float $step = null;
    public ?string $unit = null;

    public ?string $dataAction = null;
    public ?string $dataActionParam = null;
}