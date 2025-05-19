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
    public function grid_view(): void
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
    public function grid_view_with_multiple_columns(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>
                %s
                %s
                %s
                </td>
                </tr>
                ROW,
                $i + 1,
                $data['title'],
                $data['recordLabel'],
                $data['catalogueNumber'],
                date('M Y', strtotime($data['releaseDate'])),
                '<a href="/admin/record/view?id=' . $data['id'] . '">View</a>',
                '<a href="/admin/record/update?id=' . $data['id'] . '">Edit</a>',
                '<a href="/admin/record/delete?id=' . $data['id'] . '">Delete</a>',
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <table>
            <thead>
            <tr>
            <th>#</th>
            <th>Title</th>
            <th>Record Label</th>
            <th>Catalogue Number</th>
            <th>Release Date</th>
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
                {serialColumn}
                {dataColumn 'title'}
                {dataColumn 'recordLabel', header: 'Record Label'}
                {dataColumn 'catalogueNumber', header: 'Catalogue Number'}
                {dataColumn 'releaseDate', header: 'Release Date', content: fn($data) => date('M Y', strtotime($data['releaseDate']))}
                {actionColumn}
                    {actionButton 'view', 'View'}
                    {actionButton 'update', 'Edit'}
                    {actionButton 'delete', 'Delete'}
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
    public function grid_view_with_configuration(): void
    {
        $rows = [];

        foreach (self::$data as $i => $data) {
            $rows[] = sprintf(
                <<<'ROW'
                <tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>
                %s
                %s
                %s
                </td>
                </tr>
                ROW,
                $i + 1,
                $data['title'],
                $data['recordLabel'],
                $data['catalogueNumber'],
                date('M Y', strtotime($data['releaseDate'])),
                '<a href="/admin/record/view?id=' . $data['id'] . '">View</a>',
                '<a href="/admin/record/update?id=' . $data['id'] . '">Edit</a>',
                '<a href="/admin/record/delete?id=' . $data['id'] . '">Delete</a>',
            );
        }

        $expected = sprintf(
            <<<'EXPECTED'
            <div id="grid-view">
            <div>Greenslade Records</div>
            
            <table class="grid-table records">
            <thead>
            <tr>
            <th>#</th>
            <th>Title</th>
            <th>Record Label</th>
            <th>Catalogue Number</th>
            <th>Release Date</th>
            <th>Actions</th>
            </tr>
            </thead>
            <tbody class="grid-body">
            %s
            </tbody>
            </table>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED,
            implode(PHP_EOL, $rows)
        );

        $template = <<<'TEMPLATE'
            {gridView $dataReader|enableHeader: true|header: 'Greenslade Records'|containerAttributes: ['id' => 'grid-view']|addTableClass: ['grid-table', 'records']|addTbodyClass: ['grid-body']}
                {serialColumn}
                {dataColumn 'title'}
                {dataColumn 'recordLabel', header: 'Record Label'}
                {dataColumn 'catalogueNumber', header: 'Catalogue Number'}
                {dataColumn 'releaseDate', header: 'Release Date', content: fn($data) => date('M Y', strtotime($data['releaseDate']))}
                {actionColumn}
                    {actionButton 'view', 'View'}
                    {actionButton 'update', 'Edit'}
                    {actionButton 'delete', 'Delete'}
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