<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Column;

use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use Closure;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class ColumnTest extends TestCase
{
    #[Test]
    public function actionButton(): void
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
    public function actionColumn(string $expected): void
    {
        $templateFile = $this->createActionColumnTemplate();

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('checkboxColumnProvider')]
    public function checkboxColumn(string $expected): void
    {
        $templateFile = $this->createCheckboxColumnTemplate();

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('dataColumnProvider')]
    public function dataColumn(string $name, string $label, ?string $value, string $expected): void
    {
        $templateFile = $this->createDataColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('radioColumnProvider')]
    public function radioColumn(string $expected): void
    {
        $templateFile = $this->createRadioColumnTemplate();

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    #[DataProvider('serialColumnProvider')]
    public function serialColumn(string $expected): void
    {
        $templateFile = $this->createSerialColumnTemplate();

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    public function actionButtonProvider(): Generator
    {
        yield 'View' => [
            'content' => 'View',
            'url' => '/view',
            'expected' => <<<'EXPECTED'

                new Yiisoft\Yii\DataView\Column\ActionButton('View', url: '/view'),
EXPECTED
        ];
    }

    public function actionColumnProvider(): Generator
    {
        yield 'buttons' => [
            'expected' => <<<'EXPECTED'

        new Yiisoft\Yii\DataView\Column\ActionColumn(
            buttons: [
                new Yiisoft\Yii\DataView\Column\ActionButton('View', url: '/view/'),
                new Yiisoft\Yii\DataView\Column\ActionButton('Update', url: '/update/'),
                new Yiisoft\Yii\DataView\Column\ActionButton('Delete', url: '/delete/'),
            ],
        ),
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

    private function createActionColumnTemplate(): string
    {
        $buttons = [];

        foreach (['view', 'update', 'delete'] as $action)  {
            $buttons[] = "{actionButton '" . ucfirst($action) . "', url: '/$action/'}";
        }

        $template = sprintf(
            <<< 'TEMPLATE'
{actionColumn}
%s    
{/actionColumn}

TEMPLATE,
            '    ' . implode(PHP_EOL . '    ', $buttons)
        );

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'actionColumn.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createCheckboxColumnTemplate(): string
    {
        $template = <<<'TEMPLATE'
            {checkboxColumn}
            TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'checkboxColumn.latte';

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

    private function createRadioColumnTemplate(): string
    {
        $template = <<<'TEMPLATE'
            {radioColumn}
            TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'radioColumn.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }

    private function createSerialColumnTemplate(): string
    {
        $template = <<<'TEMPLATE'
            {serialColumn}
            TEMPLATE;

        $templateFile = self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'serialColumn.latte';

        file_put_contents($templateFile, $template);

        return $templateFile;
    }
}