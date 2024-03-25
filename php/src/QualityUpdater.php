<?php

declare(strict_types=1);

namespace GildedRose;

interface QualityUpdater
{
    public function canUpdate(Item $item): bool;

    public function update(Item $item): void;
}
