<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Support;

trait AssertTrait
{
    private function assert(string $templateFile, string $template, array $parameters, string $expected): void
    {
        file_put_contents($templateFile, $template);

        $this->assertSame(
            $expected,
            self::$latte
                ->renderToString(
                    $templateFile,
                    $parameters
                )
        );
    }
}