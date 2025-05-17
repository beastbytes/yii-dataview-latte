<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use PHPUnit\Framework\Attributes\Test;

final class GridViewTest extends DataViewTest
{
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