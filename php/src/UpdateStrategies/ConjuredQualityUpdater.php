<?php

declare(strict_types=1);

namespace GildedRose\UpdateStrategies;

use GildedRose\Item;
use GildedRose\ItemDetector;
use GildedRose\QualityUpdater;

use LogicException;

use function max;

final class ConjuredQualityUpdater implements QualityUpdater
{
    private const MIN_QUALITY = 0;

    public function __construct(
        private ItemDetector $itemDetector = new ItemDetector(),
    ) {
    }

    public function canUpdate(Item $item): bool
    {
        return $this->itemDetector->isConjured($item);
    }

    public function update(Item $item): void
    {
        if (! $this->itemDetector->isConjured($item)) {
            throw new LogicException(
                'Non Conjured provided to Conjured quality updater'
            );
        }

        $item->quality = max($item->quality - 2, self::MIN_QUALITY);

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            $item->quality = max($item->quality - 2, self::MIN_QUALITY);
        }
    }

    private function updateSellIn(Item $item): void
    {
        $item->sellIn = $item->sellIn - 1;
    }
}
