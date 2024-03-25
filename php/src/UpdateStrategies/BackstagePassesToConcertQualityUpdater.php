<?php

declare(strict_types=1);

namespace GildedRose\UpdateStrategies;

use GildedRose\Item;
use GildedRose\ItemDetector;
use GildedRose\QualityUpdater;
use LogicException;

final class BackstagePassesToConcertQualityUpdater implements QualityUpdater
{
    private const MAX_QUALITY = 50;

    public function __construct(
        private ItemDetector $itemDetector = new ItemDetector(),
    ) {
    }

    public function canUpdate(Item $item): bool
    {
        return $this->itemDetector->isBackstagePassesToConcert($item);
    }

    public function update(Item $item): void
    {
        if (! $this->itemDetector->isBackstagePassesToConcert($item)) {
            throw new LogicException(
                'Non BackstagePassesToConcert provided to BackstagePassesToConcert quality updater'
            );
        }

        $qualityIncrease = 1;
        if ($item->sellIn < 11) {
            $qualityIncrease++;
        }
        if ($item->sellIn < 6) {
            $qualityIncrease++;
        }

        $item->quality = $item->quality + $qualityIncrease;

        $item->quality = min($item->quality, self::MAX_QUALITY);

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            $item->quality = 0;
        }
    }

    private function updateSellIn(Item $item): void
    {
        $item->sellIn = $item->sellIn - 1;
    }
}
