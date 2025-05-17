<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use PHPUnit\Framework\Attributes\Test;

final class ListViewTest extends DataViewTest
{
    #[Test]
    public function list_view_with_item_view(): void
    {
        $expected = <<<'EXPECTED'
            <div>
            <ul>
            %s
            </ul>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED
        ;

        $items = [];
        foreach (self::$data as $data) {
            $items[] = sprintf(<<<'EXPECTED'
                <li>
                <span class="title">%s</span> by <span class="artist">%s</span>
                </li>
                EXPECTED,
                $data['title'],
                $data['artist']
            );
        }

        $expected = sprintf($expected, implode(PHP_EOL, $items));

        $template = sprintf(
            <<<'TEMPLATE'
                {listView $dataReader, '%s'}
                TEMPLATE,
            __DIR__
                . DIRECTORY_SEPARATOR . 'resources'
                . DIRECTORY_SEPARATOR . 'views'
                . DIRECTORY_SEPARATOR . 'list-item.php'
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [],
            $expected
        );
    }
    #[Test]
    public function list_view_with_item_callback(): void
    {
        $expected = <<<'EXPECTED'
            <div>
            <ul>
            %s
            </ul>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED
        ;

        $items = [];
        foreach (self::$data as $data) {
            $items[] = sprintf(
                <<<'EXPECTED'
                    <li>
                    <span class="title">%s</span> by <span class="artist">%s</span>
                    </li>
                    EXPECTED,
                $data['title'],
                $data['artist']
            );
        }

        $expected = sprintf($expected, implode(PHP_EOL, $items));

        $template = sprintf(
            <<<'TEMPLATE'
                {listView $dataReader, %s}
                TEMPLATE,
            'fn($context) => sprintf("<span class=\"title\">%s</span> by <span class=\"artist\">%s</span>\n", $context->data[\'title\'], $context->data[\'artist\'])'
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [],
            $expected
        );
    }
    #[Test]
    public function list_view_with_item_view_variable(): void
    {
        $expected = <<<'EXPECTED'
            <div>
            <ul>
            %s
            </ul>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED
        ;

        $items = [];
        foreach (self::$data as $data) {
            $items[] = sprintf(<<<'EXPECTED'
                <li>
                <span class="title">%s</span> by <span class="artist">%s</span>
                </li>
                EXPECTED,
                $data['title'],
                $data['artist']
            );
        }

        $expected = sprintf($expected, implode(PHP_EOL, $items));

        $template = '{listView $dataReader, $itemView}';

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'itemView' => __DIR__
                    . DIRECTORY_SEPARATOR . 'resources'
                    . DIRECTORY_SEPARATOR . 'views'
                    . DIRECTORY_SEPARATOR . 'list-item.php'
            ],
            $expected
        );
    }

    #[Test]
    public function list_view_with_item_callback_variable(): void
    {
        $expected = <<<'EXPECTED'
            <div>
            <ul>
            %s
            </ul>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED
        ;

        $items = [];
        foreach (self::$data as $data) {
            $items[] = sprintf(
                <<<'EXPECTED'
                    <li>
                    <span class="title">%s</span> by <span class="artist">%s</span>
                    </li>
                    EXPECTED,
                $data['title'],
                $data['artist']
            );
        }

        $expected = sprintf($expected, implode(PHP_EOL, $items));

        $template = '{listView $dataReader, $itemCallback}';

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'itemCallback' => fn($context) => sprintf("<span class=\"title\">%s</span> by <span class=\"artist\">%s</span>\n", $context->data['title'], $context->data['artist'])
            ],
            $expected
        );
    }

    private function assert(string $templateFile, string $template, array $parameters, string $expected): void
    {
        file_put_contents($templateFile, $template);

        $this->assertSame(
            $expected,
            self::$latte
                ->renderToString(
                    $templateFile,
                    array_merge($parameters, ['dataReader' => self::$dataReader])
                )
        );
    }
}