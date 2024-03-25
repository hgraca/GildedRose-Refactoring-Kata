<?php

declare(strict_types=1);

namespace GildedRose\UpdateStrategies;

use GildedRose\Item;
use GildedRose\ItemDetector;
use GildedRose\QualityUpdater;
use LogicException;

use function min;

final class AgedBrieQualityUpdater implements QualityUpdater
{
    private const MAX_QUALITY = 50;

    public function __construct(
        private ItemDetector $itemDetector = new ItemDetector(),
    ) {
    }

    public function canUpdate(Item $item): bool
    {
        return $this->itemDetector->isAgedBrie($item);
    }

    public function update(Item $item): void
    {
        if (! $this->itemDetector->isAgedBrie($item)) {
            throw new LogicException(
                'Non AgedBrie provided to AgedBrie quality updater'
            );
        }

        $item->quality = min($item->quality + 1, self::MAX_QUALITY);
        $this->updateSellIn($item);
        if ($item->sellIn < 0) {
            $item->quality = min($item->quality + 1, self::MAX_QUALITY);
        }
    }

    private function updateSellIn(Item $item): void
    {
        $item->sellIn = $item->sellIn - 1;
    }
}
