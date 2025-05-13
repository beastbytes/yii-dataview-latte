<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Yiisoft\Yii\DataView\Column\ActionButton;
use Yiisoft\Yii\DataView\Column\ActionColumn;
use Yiisoft\Yii\DataView\Column\CheckboxColumn;
use Yiisoft\Yii\DataView\Column\DataColumn;
use Yiisoft\Yii\DataView\Column\RadioColumn;
use Yiisoft\Yii\DataView\Column\SerialColumn;

final class ColumnTest extends TestBase
{
    #[Test]
    public function actionButton(): void
    {
    }

    #[Test]
    public function actionColumn(): void
    {
    }

    #[Test]
    public function checkboxColumn(): void
    {
    }

    #[Test]
    #[DataProvider('dataColumnProvider')]
    public function dataColumn($name, $label, $value, $expected): void
    {
        $templateFile = $this->createDataColumnTemplate($name, $label, $value);

        $actual = self::$latte
            ->renderToString($templateFile)
        ;

        $this->assertSame($expected, $actual);
    }

    #[Test]
    public function radioColumn(): void
    {
    }

    #[Test]
    public function serialColumn(): void
    {
    }

    public static function dataColumnProvider(): \Generator
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
}