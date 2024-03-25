<?php

declare(strict_types=1);

namespace GildedRose;

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
            if (!$this->itemDetector->isAgedBrie($item) and !$this->itemDetector->isBackstagePassesToConcert($item)) {
                if ($item->quality > 0) {
                    if (!$this->itemDetector->isSulfuras($item)) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($this->itemDetector->isBackstagePassesToConcert($item)) {
                        if ($item->sellIn < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sellIn < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }

            $this->updateSellIn($item);

            if ($item->sellIn < 0) {
                if (!$this->itemDetector->isAgedBrie($item)) {
                    if (!$this->itemDetector->isBackstagePassesToConcert($item)) {
                        if ($item->quality > 0) {
                            if (!$this->itemDetector->isSulfuras($item)) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }

    private function updateSellIn(Item $item): void
    {
        if (! $this->itemDetector->isSulfuras($item)) {
            $item->sellIn = $item->sellIn - 1;
        }
    }
}
