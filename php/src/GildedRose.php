<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\UpdateStrategies\StandardItemQualityUpdater;

final class GildedRose
{
    /**
     * @var array<QualityUpdater>
     */
    private array $qualityUpdaterStrategies;

    private StandardItemQualityUpdater $standardItemQualityUpdaterStrategy;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items,
        QualityUpdater ...$qualityUpdaterStrategies,
    ) {
        $this->qualityUpdaterStrategies = $qualityUpdaterStrategies;
        $this->standardItemQualityUpdaterStrategy = new StandardItemQualityUpdater();
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateItemQuality($item);
        }
    }

    private function updateItemQuality(Item $item): void
    {
        $this->findUpdateStrategy($item)->update($item);
    }

    private function findUpdateStrategy(Item $item): QualityUpdater
    {
        foreach ($this->qualityUpdaterStrategies as $qualityUpdaterStrategy) {
            if ($qualityUpdaterStrategy->canUpdate($item)) {
                return $qualityUpdaterStrategy;
            }
        }

        return $this->standardItemQualityUpdaterStrategy;
    }
}
