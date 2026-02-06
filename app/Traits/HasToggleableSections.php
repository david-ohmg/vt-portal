<?php

namespace App\Traits;

trait HasToggleableSections
{
    public ?int $openId = null;

    /**
     * Toggle a section open/closed
     */
    public function toggle(?int $id): void
    {
        $this->openId = $this->openId === $id ? null : $id;
    }

    /**
     * Check if a section is open
     */
    public function isOpen(?int $id): bool
    {
        return $this->openId === $id;
    }
}
