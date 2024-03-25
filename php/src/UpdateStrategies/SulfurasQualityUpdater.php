<?php

declare(strict_types=1);

namespace GildedRose\UpdateStrategies;

use GildedRose\Item;
use GildedRose\ItemDetector;
use GildedRose\QualityUpdater;
use LogicException;

final class SulfurasQualityUpdater implements QualityUpdater
{
    public function __construct(
        private ItemDetector $itemDetector = new ItemDetector(),
    ) {
    }

    public function canUpdate(Item $item): bool
    {
        return $this->itemDetector->isSulfuras($item);
    }

    public function update(Item $item): void
    {
        if (! $this->itemDetector->isSulfuras($item)) {
            throw new LogicException(
                'Non Sulfuras provided to Sulfuras quality updater'
            );
        }
    }
}
