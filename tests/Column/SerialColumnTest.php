<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Column;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\DataReaderTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\GridViewTestTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class SerialColumnTest extends TestCase
{
    use AssertTrait;
    use DataReaderTrait;
    use GridViewTestTrait;

    #[Test]
    public function serial_column(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td>%d</td>
                </tr>
                ROW,
                $i + 1
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>#</th>
            </tr>
            </thead>
            <tbody>
            %s
            </tbody>
            </table>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED,
            implode(PHP_EOL, $rows)
        );

        $template = <<<'TEMPLATE'
            {gridView $dataReader}
                {serialColumn}
            {/gridView}
            TEMPLATE
        ;

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader
            ],
            $expected
        );
    }

    #[Test]
    public function serial_column_with_parameters(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td class="serial">%d</td>
                </tr>
                ROW,
                $i + 1
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>Index</th>
            </tr>
            </thead>
            <tbody>
            %s
            </tbody>
            </table>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED,
            implode(PHP_EOL, $rows)
        );

        $template = <<<'TEMPLATE'
            {gridView $dataReader}
                {serialColumn header: 'Index', bodyAttributes: ['class' => 'serial']}
            {/gridView}
            TEMPLATE
        ;

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader
            ],
            $expected
        );
    }
}