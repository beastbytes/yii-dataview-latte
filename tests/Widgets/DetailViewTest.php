<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Widgets;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;

final class DetailViewTest extends TestCase
{
    use AssertTrait;

    private static array $detail;
    private static string $expectedFields;
    private static string $templateFields;

    #[Before]
    public static function before(): void
    {
        $expectedFields = [];
        $templateFields = [];

        self::$detail = self::$data[0];

        foreach (self::$fields as $field) {
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <dt>%s</dt>
                <dd>%s</dd>
                FIELD,
                $field,
                self::$detail[$field]
            );
            $templateFields[] = sprintf("{dataField '%s'}", $field);
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);
        self::$templateFields = implode(PHP_EOL, $templateFields);
    }

    #[Test]
    public function detail_view(): void
    {
        $expected = sprintf(
            <<<'EXPECTED'
            <dl>
            %s
            </dl>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data}
            %s
            {/detailView}
            TEMPLATE,
            self::$templateFields
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'data' => self::$detail
            ],
            $expected
        );
    }

    #[Test]
    public function detail_view_with_attributes(): void
    {
        $expected = sprintf(
            <<<'EXPECTED'
            <dl class="detail-view">
            %s
            </dl>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|listAttributes: ["class" => "detail-view"]}
            %s
            {/detailView}
            TEMPLATE,
            self::$templateFields
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'data' => self::$detail
            ],
            $expected
        );
    }

    #[Test]
    public function detail_view_with_field_attributes(): void
    {
        foreach (self::$fields as $field) {
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <li class="field">
                <span>%s</span>
                <span>%s</span>
                </li>
                FIELD,
                $field,
                self::$detail[$field]
            );
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);

        $expected = sprintf(
            <<<'EXPECTED'
            <ul>
            %s
            </ul>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data
                |fieldTag: 'li'
                |listTag: 'ul'
                |fieldAttributes: ['class' => 'field']
                |labelTag: 'span'
                |valueTag: 'span'
            }
            %s
            {/detailView}
            TEMPLATE,
            self::$templateFields
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'data' => self::$detail
            ],
            $expected
        );
    }

    #[Test]
    public function detail_view_with_field_list_attributes(): void
    {
        $expected = sprintf(
            <<<'EXPECTED'
            <dl class="field-list">
            %s
            </dl>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|listAttributes: ['class' => 'field-list']}
            %s
            {/detailView}
            TEMPLATE,
            self::$templateFields
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'data' => self::$detail
            ],
            $expected
        );
    }

    #[Test]
    public function detail_view_with_tags(): void
    {
        foreach (self::$fields as $field) {
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <li>
                <span>%s</span>
                <span>%s</span>
                </li>
                FIELD,
                $field,
                self::$detail[$field]
            );
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);

        $expected = sprintf(
            <<<'EXPECTED'
            <ol>
            %s
            </ol>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data
                |listTag: "ol"
                |fieldTag: "li"
                |labelTag: "span"
                |valueTag: "span"
            }
            %s
            {/detailView}
            TEMPLATE,
            self::$templateFields
        );

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            $template,
            [
                'data' => self::$detail
            ],
            $expected
        );
    }

    #[Test]
    public function detail_view_with_label_value_attributes_and_tags(): void
    {
        {
            foreach (self::$fields as $field) {
                $expectedFields[] = sprintf(
                    <<<'FIELD'
                <li>
                <span class="label">%s</span>
                <span class="value">%s</span>
                </li>
                FIELD,
                    $field,
                    self::$detail[$field]
                );
            }

            self::$expectedFields = implode(PHP_EOL, $expectedFields);

            $expected = sprintf(
                <<<'EXPECTED'
            <ol>
            %s
            </ol>
            EXPECTED,
                self::$expectedFields
            );

            $template = sprintf(
                <<<'TEMPLATE'
            {detailView $data
                |listTag: 'ol'
                |fieldTag: 'li'
                |labelTag: 'span'
                |labelAttributes: ['class' => 'label']
                |valueTag: 'span'
                |valueAttributes: ['class' => 'value']
            }
            %s
            {/detailView}
            TEMPLATE,
                self::$templateFields
            );

            $this->assert(
                self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
                $template,
                [
                    'data' => self::$detail
                ],
                $expected
            );
        }
    }
}