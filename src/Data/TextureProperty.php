<?php

namespace Microwin7\TextureProvider\Data;

use Microwin7\PHPUtils\Utils\GDUtils;

final class TextureProperty
{
    public \GdImage $image;
    public int $w;
    public int $h;
    public int $fraction;


    public function __construct(public readonly string $dataOrigin)
    {
        [$this->image, $this->w, $this->h, $this->fraction] = GDUtils::pre_calculation($this->dataOrigin);
    }
}
