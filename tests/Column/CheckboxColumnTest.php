<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Column;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\DataReaderTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\GridViewTestTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CheckboxColumnTest extends TestCase
{
    use AssertTrait;
    use DataReaderTrait;
    use GridViewTestTrait;

    #[Test]
    public function checkbox_column(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(<<<'ROW'
                <tr>
                <td><input type="checkbox" name="checkbox-selection" value="%d"></td>
                </tr>
                ROW,
                $i
            );
        }

        $expected = sprintf(<<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th><input type="checkbox" name="checkbox-selection-all" value="1"></th>
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
                {checkboxColumn}
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
    public function checkbox_column_with_parameters(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(<<<'ROW'
                <tr>
                <td class="checkbox"><input type="checkbox" name="checkbox" value="%d"></td>
                </tr>
                ROW,
                $i
            );
        }

        $expected = sprintf(<<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>Checkbox</th>
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
                {checkboxColumn header: 'Checkbox', bodyAttributes: ['class' => 'checkbox'], name: 'checkbox'}
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