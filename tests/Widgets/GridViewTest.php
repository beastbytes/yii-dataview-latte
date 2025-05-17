<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Widgets;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\DataReaderTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\GridViewTestTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GridViewTest extends TestCase
{
    use AssertTrait;
    use DataReaderTrait;
    use GridViewTestTrait;

    #[Test]
    public function gridView(string $expected): void
    {
        $templateFile = $this->createGridViewTemplate();

        $actual = self::$latte
            ->renderToString(
                $templateFile,
                [
                    'dataReader' => self::$dataReader,
                ]
            )
        ;

        $this->assertSame($expected, $actual);
    }
}