<?php

declare(strict_types=1);

namespace GildedRose;

use LogicException;

use function max;
use function min;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items,
        private ItemDetector $itemDetector = new ItemDetector(),
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateItemQuality($item);
        }
    }

    private function updateSellIn(Item $item): void
    {
        if (! $this->itemDetector->isSulfuras($item)) {
            $item->sellIn = $item->sellIn - 1;
        }
    }

    private function updateItemQuality(Item $item): void
    {
        if ($this->itemDetector->isBackstagePassesToConcert($item)) {
            $this->updateQualityOfBackstagePassesToConcert($item);
            return;
        }

        if ($this->itemDetector->isSulfuras($item)) {
            $this->updateQualityOfSulfuras($item);
            return;
        }

        if ($this->itemDetector->isAgedBrie($item)) {
            $this->updateQualityOfAgedBrie($item);
            return;
        }

        $item->quality = max($item->quality - 1, 0);

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            $item->quality = max($item->quality - 1, 0);
        }
    }

    private function updateQualityOfBackstagePassesToConcert(Item $item): void
    {
        $maxQuality = 50;

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

        $item->quality = $item->quality > $maxQuality ? $maxQuality : $item->quality;

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            $item->quality = 0;
        }
    }

    private function updateQualityOfSulfuras(Item $item): void
    {
        $this->updateSellIn($item);
    }

    private function updateQualityOfAgedBrie(Item $item): void
    {
        $maxQuality = 50;

        $item->quality = min($item->quality + 1, $maxQuality);
        $this->updateSellIn($item);
        if ($item->sellIn < 0) {
            $item->quality = min($item->quality + 1, $maxQuality);
        }
    }
}
