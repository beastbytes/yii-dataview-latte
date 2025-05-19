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
                <div>
                <dt>%s</dt>
                <dd>%s</dd>
                </div>
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
            <div>
            <dl>
            %s
            </dl>
            </div>
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
    public function detail_view_with_header(): void
    {
        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <div>Detail View</div>
            <dl>
            %s
            </dl>
            </div>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|header: '<div>Detail View</div>'}
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
            <div class="detail-view">
            <dl>
            %s
            </dl>
            </div>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|attributes: ["class" => "detail-view"]}
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
                <div class="field">
                <dt>%s</dt>
                <dd>%s</dd>
                </div>
                FIELD,
                $field,
                self::$detail[$field]
            );
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <dl>
            %s
            </dl>
            </div>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|fieldAttributes: ['class' => 'field']}
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
            <div>
            <dl class="field-list">
            %s
            </dl>
            </div>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data|fieldListAttributes: ['class' => 'field-list']}
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
    public function detail_view_with_templates_and_tags(): void
    {
        foreach (self::$fields as $field) {
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <tr>
                <th>%s</th>
                <td>%s</td>
                </tr>
                FIELD,
                $field,
                self::$detail[$field]
            );
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);

        $expected = sprintf(
            <<<'EXPECTED'
            <table>
            <tbody>
            %s
            </tbody>
            </table>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data
                |fieldTemplate: "<tr>\n{label}\n{value}\n</tr>"
                |template: "<table>\n<tbody>\n{fields}\n</tbody>\n</table>"
                |labelTag: "th"
                |valueTag: "td"
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
        foreach (self::$fields as $field) {
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <span class="label">%s</span>
                <span class="value">%s</span>
                </div>
                FIELD,
                $field,
                self::$detail[$field]
            );
        }

        self::$expectedFields = implode(PHP_EOL, $expectedFields);

        $expected = sprintf(
            <<<'EXPECTED'
            <div>
            <dl>
            %s
            </dl>
            </div>
            EXPECTED,
            self::$expectedFields
        );

        $template = sprintf(
            <<<'TEMPLATE'
            {detailView $data
                |labelAttributes: ['class' => 'label']
                |labelTag: 'span'
                |valueAttributes: ['class' => 'value']
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
}