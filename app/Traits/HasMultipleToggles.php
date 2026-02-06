<?php

namespace App\Traits;

trait HasMultipleToggles
{
    /**
     * Toggle the main section
     */
    public function toggle(?int $id): void
    {
        $this->openId = $this->openId === $id ? null : $id;
    }

    /**
     * Toggle the AA section
     */
    public function aa_toggle(?int $id): void
    {
        $this->aaId = $this->aaId === $id ? null : $id;
    }

    /**
     * Check if a section is open
     */
    protected function isOpen(string $property, ?int $id): bool
    {
        return $this->$property === $id;
    }
}
