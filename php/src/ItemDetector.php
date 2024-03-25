<?php

declare(strict_types=1);

namespace GildedRose;

final class ItemDetector
{
    public function isAgedBrie(Item $item): bool
    {
        return $item->name === 'Aged Brie';
    }

    public function isBackstagePassesToConcert(Item $item): bool
    {
        return $item->name === 'Backstage passes to a TAFKAL80ETC concert';
    }

    public function isSulfuras(Item $item): bool
    {
        return $item->name === 'Sulfuras, Hand of Ragnaros';
    }
}
