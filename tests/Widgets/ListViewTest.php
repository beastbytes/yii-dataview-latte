<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Widgets;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\DataReaderTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ListViewTest extends TestCase
{
    use AssertTrait;
    use DataReaderTrait;

    private const VIEW_DIR = __DIR__
        . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'Support'
        . DIRECTORY_SEPARATOR . 'views'
    ;
    private const VIEW_FILE = 'list-item.php';

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
            {listView $dataReader, '%s'}
            TEMPLATE,
            self::VIEW_DIR . DIRECTORY_SEPARATOR . self::VIEW_FILE
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader,
            ],
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
            [
                'dataReader' => self::$dataReader,
            ],
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

        $template = <<<'TEMPLATE'
            {listView $dataReader, $itemView}
            TEMPLATE;

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader,
                'itemView' => self::VIEW_DIR . DIRECTORY_SEPARATOR . self::VIEW_FILE,
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

        $template =  <<<'TEMPLATE'
            {listView $dataReader, $itemCallback}
            TEMPLATE;

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader,
                'itemCallback' => fn($context) => sprintf("<span class=\"title\">%s</span> by <span class=\"artist\">%s</span>\n", $context->data['title'], $context->data['artist'])
            ],
            $expected
        );
    }

    #[Test]
    public function list_view_with_configuration(): void
    {
        $expected = <<<'EXPECTED'
            <div id="list-view">
            <div>
            %s
            </div>
            <div>Page <b>1</b> of <b>1</b></div>
            </div>
            EXPECTED
        ;

        $items = [];
        foreach (self::$data as $data) {
            $items[] = sprintf(
                <<<'EXPECTED'
                <div>
                <span class="title">%s</span> by <span class="artist">%s</span>
                </div>
                EXPECTED,
                $data['title'],
                $data['artist']
            );
        }

        $expected = sprintf($expected, implode(PHP_EOL, $items));

        $template = <<<'TEMPLATE'
            {listView $dataReader, $itemCallback|id: 'list-view'|itemTag: 'div'|itemListTag: 'div'}
            TEMPLATE;

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'dataReader' => self::$dataReader,
                'itemCallback' => fn($context) => sprintf("<span class=\"title\">%s</span> by <span class=\"artist\">%s</span>\n", $context->data['title'], $context->data['artist'])
            ],
            $expected
        );
    }
}