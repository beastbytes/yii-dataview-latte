<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Column;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\DataReaderTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\GridViewTestTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ActionButtonTest extends TestCase
{
    use AssertTrait;
    use DataReaderTrait;
    use GridViewTestTrait;

    #[Test]
    public function action_button(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td>
                %s
                </td>
                </tr>
                ROW,
                '<a href="/admin/record/view?id=' . $data['id'] . '">View</a>'
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>Actions</th>
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
                {actionColumn}
                    {actionButton 'view', 'View'}
                {/actionColumn}
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
    public function action_button_with_named_arguments(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td>
                %s
                </td>
                </tr>
                ROW,
                '<a href="/admin/record/view?id=' . $data['id'] . '">View</a>'
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>Actions</th>
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
                {actionColumn}
                    {actionButton content: 'View',  name: 'view'}
                {/actionColumn}
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