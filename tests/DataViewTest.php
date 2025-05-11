<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use BeastBytes\View\Latte\LatteFactory;
use BeastBytes\Yii\DataView\Latte\DataViewExtension;
use Generator;
use Latte\Engine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Yiisoft\Files\FileHelper;
use Yiisoft\Yii\DataView\Field\DataField;

final class DataViewTest extends TestBase
{
    #[Test]
    #[DataProvider('detailViewProvider')]
    public function detailView(array|object $data, array $fields, string $expected): void
    {
        $templateFile = $this->createDetailViewTemplate();

        $actual = self::$latte
            ->renderToString(
                $templateFile,
                [
                    'data' => $data,
                    'fields' => $fields,
                ]
            )
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    public function gridView(): void
    {
    }

    #[Test]
    public function listView(): void
    {
    }

    public static function detailViewProvider(): Generator
    {
        $data = require __DIR__ . '/resources/data.php';

        $detail = $data[0];
        $fields = array_keys($detail);
        $fields = array_map(fn($field) => new DataField('$field'), $fields);

        yield 'detailView' => [
            'data' => $detail,
            'fields' => $fields,
            'expected' => <<< 'EXPECTED'
Yiisoft\Yii\DataView\DetailView::widget()
    ->data($data)
    ->fields(...$fields)
;
EXPECTED,
        ];
    }

    private function createDetailViewTemplate(): string
    {
        $template = <<< 'TEMPLATE'
{varType array|object $data}
{varType array $fields}

{detailView $data $fields}
TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'detailView-' . md5($template) . '.latte';
        file_put_contents($templateFile, $template);

        return $templateFile;
    }
}