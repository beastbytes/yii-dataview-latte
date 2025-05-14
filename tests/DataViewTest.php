<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use Generator;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Yii\DataView\Field\DataField;

final class DataViewTest extends TestBase
{
    protected static ReadableDataInterface $dataReader;

    #[BeforeClass]
    public static function before(): void
    {
        self::$dataReader = (new OffsetPaginator(new IterableDataReader(self::$data)))
            ->withPageSize(10)
        ;
    }

    #[Test]
    #[DataProvider('detailViewProvider')]
    public function detailView(string $expected): void
    {
        $templateFile = $this->createDetailViewTemplate();

        $actual = self::$latte
            ->renderToString(
                $templateFile,
                [
                    'data' => self::$data[0],
                    'fields' => array_map(fn($field) => new DataField('$field'), self::$fields),
                ]
            )
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('gridViewProvider')]
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

    #[Test]
    #[DataProvider('listViewProvider')]
    public function listView(string $expected): void
    {
        $templateFile = $this->createListViewTemplate();

        $actual = self::$latte
            ->renderToString(
                $templateFile,
                [
                    'dataReader' => self::$dataReader,
                    'itemView' => 'list-item',
                ]
            )
        ;

        $this->assertSame($expected, $actual);
    }

    public static function detailViewProvider(): Generator
    {
        yield 'detailView' => [
            'expected' => <<< 'EXPECTED'
Yiisoft\Yii\DataView\DetailView::widget()
    ->data($data)
    ->fields(
        new Yiisoft\Yii\DataView\Field\DataField('id'),
        new Yiisoft\Yii\DataView\Field\DataField('artist'),
        new Yiisoft\Yii\DataView\Field\DataField('title'),
        new Yiisoft\Yii\DataView\Field\DataField('recordLabel'),
        new Yiisoft\Yii\DataView\Field\DataField('catalogueNumber'),
        new Yiisoft\Yii\DataView\Field\DataField('releaseDate'),
    )
;
EXPECTED,
        ];
    }

    public static function gridViewProvider(): Generator
    {
        yield 'gridView' => [
            'expected' => <<< 'EXPECTED'
Yiisoft\Yii\DataView\GridView::widget()
    ->dataReader($dataReader)
    ->columns(
        new Yiisoft\Yii\DataView\Column\DataColumn('id'),
        new Yiisoft\Yii\DataView\Column\DataColumn('artist'),
        new Yiisoft\Yii\DataView\Column\DataColumn('title'),
        new Yiisoft\Yii\DataView\Column\DataColumn('recordLabel'),
        new Yiisoft\Yii\DataView\Column\DataColumn('catalogueNumber'),
        new Yiisoft\Yii\DataView\Column\DataColumn('releaseDate'),
        new Yiisoft\Yii\DataView\Column\ActionColumn(
            buttons: [
                new Yiisoft\Yii\DataView\Column\ActionButton('View', url: '/view/'),
                new Yiisoft\Yii\DataView\Column\ActionButton('Update', url: '/update/'),
                new Yiisoft\Yii\DataView\Column\ActionButton('Delete', url: '/delete/'),
            ],
        ),
    )
;
EXPECTED,
        ];
    }

    public static function listViewProvider(): Generator
    {
        yield 'listView' => [
            'expected' => <<< 'EXPECTED'
Yiisoft\Yii\DataView\ListView::widget()
    ->dataReader($dataReader)
    ->itemView($itemView)
;
EXPECTED,
        ];
    }

    private function createDetailViewTemplate(): string
    {
        $template = <<< 'TEMPLATE'
{varType array|object $data}

{detailView $data}
    {dataField 'id'}
    {dataField 'artist'}
    {dataField 'title'}
    {dataField 'recordLabel'}
    {dataField 'catalogueNumber'}
    {dataField 'releaseDate'}
{/detailView}
TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'detailView-' . md5($template) . '.latte';
        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createGridViewTemplate(): string
    {
        $buttons = [];
        $columns = [];

        foreach (self::$fields as $field) {
            $columns[] = "{dataColumn '$field'}";
        }

        foreach (['view', 'update', 'delete'] as $action)  {
            $buttons[] = "{actionButton '" . ucfirst($action) . "', url: '/$action/'}";
        }

        $columns[] = sprintf(
            <<< 'COLUMN'
{actionColumn}
%s    
{/actionColumn}
COLUMN,
            '    ' . implode(PHP_EOL . '    ', $buttons)
        );

        $template = sprintf(
            <<< 'TEMPLATE'
{varType ReadableDataInterface $dataReader}

{gridView $dataReader}
%s
{/gridView}
TEMPLATE,
            '    ' . implode(PHP_EOL . '    ', $columns)
        );

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'gridView-' . md5($template) . '.latte';
        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createListViewTemplate(): string
    {
        $template = <<< 'TEMPLATE'
{varType ReadableDataInterface $dataReader}
{varType string $itemView}

{listView $dataReader $itemView}
TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'listView-' . md5($template) . '.latte';
        file_put_contents($templateFile, $template);

        return $templateFile;
    }
}