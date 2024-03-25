<?php

declare(strict_types=1);

namespace GildedRose;

use LogicException;

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
        if (! $this->itemDetector->isAgedBrie($item)) {
            if ($item->quality > 0) {
                if (! $this->itemDetector->isSulfuras($item)) {
                    $item->quality = $item->quality - 1;
                }
            }
        } else {
            if ($item->quality < 50) {
                $item->quality = $item->quality + 1;
            }
        }

        $this->updateSellIn($item);

        if ($item->sellIn < 0) {
            if (! $this->itemDetector->isAgedBrie($item)) {
                if ($item->quality > 0) {
                    if (! $this->itemDetector->isSulfuras($item)) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                }
            }
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
}
