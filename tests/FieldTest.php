<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use Closure;
use DateTime;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class FieldTest extends TestBase
{
    #[Test]
    #[DataProvider('dataFieldProvider')]
    public function dataField(
        string $name,
        string $label,
        Closure|string|null $value,
        string $expected
    ): void
    {
        $templateFile = $this->createTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    public static function dataFieldProvider(): Generator
    {
        $data = require __DIR__ . '/resources/data.php';
        $detail = $data[0];

        yield 'id' => [
            'name' => 'id',
            'label' => '',
            'value' => null,
            'expected' => <<<'EXPECTED'

        new Yiisoft\Yii\DataView\Field\DataField('id'),
EXPECTED,
        ];
        yield 'artist' => [
            'name' => 'artist',
            'label' => 'Group',
            'value' => null,
            'expected' => <<<'EXPECTED'

        new Yiisoft\Yii\DataView\Field\DataField('artist'),
EXPECTED,
        ];
        yield 'title' => [
            'name' => 'title',
            'label' => 'Title',
            'value' => null,
            'expected' => <<<'EXPECTED'

        new Yiisoft\Yii\DataView\Field\DataField('title'),
EXPECTED,
        ];
        yield 'recordLabel' => [
            'name' => 'recordLabel',
            'label' => 'Label',
            'value' => $detail['recordLabel'],
            'expected' => <<<EXPECTED

        new Yiisoft\Yii\DataView\Field\DataField('recordLabel'),
EXPECTED,
        ];
        yield 'catalogueNumber' => [
            'name' => 'catalogueNumber',
            'label' => '',
            'value' => null,
            'expected' => <<<'EXPECTED'

        new Yiisoft\Yii\DataView\Field\DataField('catalogueNumber'),
EXPECTED,
        ];
        /*
        yield 'releaseDate' => [
            'name' => 'releaseDate',
            'label' => '',
            'value' => fn($data) => (new Datetime($data))->format('M Y'),
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Field\DataField('releaseDate', value: fn($data) => (new Datetime($data))->format('M Y')),
EXPECTED,
        ];
        */
    }

    private function createTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = sprintf(
            <<<'TEMPLATE'
            {dataField '%s'}
            TEMPLATE,
            $name,
        );

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }
}