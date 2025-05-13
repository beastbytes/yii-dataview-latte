<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use Closure;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class ColumnTest extends TestBase
{
    #[Test]
    #[DataProvider('actionButtonProvider')]
    public function actionButton(string $content, string $url, string $expected): void
    {
        $templateFile = $this->createActionButtonTemplate($content, $url);

        $actual = self::$latte
            ->renderToString(
                $templateFile,
                [
                    'content' => $content,
                    'url' => $url,
                ]
            )
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('actionColumnProvider')]
    public function actionColumn(string $name, string $label, string $value, string $expected): void
    {
        $templateFile = $this->createActionColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('checkboxColumnProvider')]
    public function checkboxColumn(string $name, string $label, string $value, string $expected): void
    {
        $templateFile = $this->createCheckboxColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('dataColumnProvider')]
    public function dataColumn(string $name, string $label, string $value, string $expected): void
    {
        $templateFile = $this->createDataColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('radioColumnProvider')]
    public function radioColumn(string $name, string $label, string $value, string $expected): void
    {
        $templateFile = $this->createRadioColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('serialColumnProvider')]
    public function serialColumn(string $name, string $label, string $value, string $expected): void
    {
        $templateFile = $this->createSerialColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    public function actionButtonProvider(): Generator
    {
        yield [
            'content' => 'View',
            'url' => '/view',
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\ActionButton('View', url: '/view'),
EXPECTED
        ];
    }

    public function actionColumnProvider(): Generator
    {
        yield [
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\ActionColumn(),
EXPECTED
        ];
    }

    public function checkboxColumnProvider(): Generator
    {
        yield [
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\CheckboxColumn(),
EXPECTED
        ];
    }

    public static function dataColumnProvider(): Generator
    {
        yield 'id' => [
            'name' => 'id',
            'label' => '',
            'value' => null,
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\DataColumn('id'),
EXPECTED
        ];
    }

    public static function radioColumnProvider(): Generator
    {
        yield [
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\RadioColumn(),
EXPECTED
        ];
    }

    public static function serialColumnProvider(): Generator
    {
        yield [
            'expected' => <<<'EXPECTED'

new Yiisoft\Yii\DataView\Column\SerialColumn(),
EXPECTED
        ];
    }

    private function createActionButtonTemplate(
        string $content,
        string $url,
    ): string
    {
        $template = sprintf(
            <<<'TEMPLATE'
                        
            {actionButton '%s', url: '%s'}
            TEMPLATE,
            $content,
            $url,
        );;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'actionButton-' . strtolower($content) . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createActionColumnTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = '';

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'actionColumn-' . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createCheckboxColumnTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = '';

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'checkboxColumn-' . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createDataColumnTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = sprintf(
            <<<'TEMPLATE'
            {dataColumn '%s'}
            TEMPLATE,
            $name,
        );

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'dataColumn-' . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createRadioColumnTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = '';

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'radioColumn-' . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createSerialColumnTemplate(
        string $name,
        string $label,
        Closure|string|null $value,
    ): string
    {
        $template = '';

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'serialColumn-' . $name . '.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }
}