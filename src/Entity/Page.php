<?php

namespace App\Entity;

class Page
{
    public static function createFromArray(array $item): self
    {
        return new Page();
    }
}
