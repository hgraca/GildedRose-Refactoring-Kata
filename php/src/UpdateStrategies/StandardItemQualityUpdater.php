<?php

declare(strict_types=1);

namespace GildedRose\UpdateStrategies;

use GildedRose\Item;
use GildedRose\QualityUpdater;

use function max;

final class StandardItemQualityUpdater implements QualityUpdater
{
    private const MIN_QUALITY = 0;

    public function canUpdate(Item $item): bool
    {
        return true;
    }

    public function update(Item $item): void
    {
        $item->quality = max($item->quality - 1, self::MIN_QUALITY);

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            $item->quality = max($item->quality - 1, self::MIN_QUALITY);
        }
    }

    private function updateSellIn(Item $item): void
    {
        $item->sellIn = $item->sellIn - 1;
    }
}
