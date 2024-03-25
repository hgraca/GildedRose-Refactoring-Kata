<?php

declare(strict_types=1);

namespace Tests;

use ApprovalTests\Approvals;
use GildedRose\GildedRose;
use GildedRose\Item;
use GildedRose\UpdateStrategies\AgedBrieQualityUpdater;
use GildedRose\UpdateStrategies\BackstagePassesToConcertQualityUpdater;
use GildedRose\UpdateStrategies\SulfurasQualityUpdater;
use PHPUnit\Framework\TestCase;

/**
 * This unit test uses [Approvals](https://github.com/approvals/ApprovalTests.php).
 *
 * There are two test cases here with different styles:
 * <li>"foo" is more similar to the unit test from the 'Java' version
 * <li>"thirtyDays" is more similar to the TextTest from the 'Java' version
 *
 * I suggest choosing one style to develop and deleting the other.
 */
class ApprovalTest extends TestCase
{
    public function testFoo(): void
    {
        $items = [new Item('foo', 0, 0)];
        $app = new GildedRose(
            $items,
            new BackstagePassesToConcertQualityUpdater(),
            new AgedBrieQualityUpdater(),
            new SulfurasQualityUpdater(),
        );
        $app->updateQuality();

        Approvals::verifyList($items);
    }

    public function testThirtyDays(): void
    {
        ob_start();

        $argv = ['', '30'];
        include(__DIR__ . '/../fixtures/texttest_fixture.php');

        $output = ob_get_clean();

        Approvals::approveString($output);
    }
}
