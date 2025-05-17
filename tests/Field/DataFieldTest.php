<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Field;

use BeastBytes\Yii\DataView\Latte\Tests\Support\AssertTrait;
use BeastBytes\Yii\DataView\Latte\Tests\Support\TestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\Test;

final class DataFieldTest extends TestCase
{
    use AssertTrait;

    private const EXPECTED = <<<EXPECTED
        <div>
        <dl>
        %s
        </dl>
        </div>
        EXPECTED
    ;

    private const TEMPLATE = <<<'TEMPLATE'
        {detailView $data}
        %s
        {/detailView}
        TEMPLATE
    ;

    private static array $detail;

    #[BeforeClass]
    public static function beforeClass(): void
    {
        parent::beforeClass();
        self::$detail = self::$data[0];
    }

    #[Test]
    public function data_field(): void
    {
        $expectedFields = [];
        $fields = [];

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
            $fields[] = sprintf("{dataField '%s'}", $field);
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }

    #[Test]
    public function data_field_with_label(): void
    {
        $expectedFields = [];
        $fields = [];

        foreach (self::$fields as $field) {
            $label = self::$inflector->toSentence(ucfirst($field), uppercaseAll: true);
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <dt>%s</dt>
                <dd>%s</dd>
                </div>
                FIELD,
                $label,
                self::$detail[$field]
            );
            $fields[] = sprintf(
                "{dataField '%s', %s}",
                $field,
                'label: \'' . $label . '\''
            );
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }

    #[Test]
    public function data_field_with_label_and_value(): void
    {
        $expectedFields = [];
        $fields = [];

        foreach (self::$fields as $field) {
            $label = self::$inflector->toSentence(ucfirst($field), uppercaseAll: true);

            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <dt>%s</dt>
                <dd>%s</dd>
                </div>
                FIELD,
                $label,
                ($field === 'releaseDate'
                    ? date('Y-m-d', strtotime(self::$detail['releaseDate']))
                    : self::$detail[$field]
                )
            );
            $fields[] = sprintf(
                "{dataField '%s', %s, %s}",
                $field,
                'label: \'' . $label . '\'',
                'value: ' . ($field === 'releaseDate'
                    ? 'fn($data) => date(\'Y-m-d\', strtotime($data[\'releaseDate\']))'
                    : 'null'
                )
            );
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }

    #[Test]
    public function data_field_with_label_and_value_no_field(): void
    {
        $expectedFields = [];
        $fields = [];

        foreach (self::$fields as $field) {
            $label = self::$inflector->toSentence(ucfirst($field), uppercaseAll: true);

            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <dt>%s</dt>
                <dd>%s</dd>
                </div>
                FIELD,
                $label,
                ($field === 'releaseDate'
                    ? date('Y-m-d', strtotime(self::$detail['releaseDate']))
                    : self::$detail[$field]
                )
            );
            $fields[] = sprintf(
                "{dataField %s, %s}",
                'label: \'' . $label . '\'',
                'value: ' . ($field === 'releaseDate'
                    ? 'fn($data) => date(\'Y-m-d\', strtotime($data[\'releaseDate\']))'
                    : "'" . self::$detail[$field] . "'"
                )
            );
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }

    #[Test]
    public function data_field_with_label_and_value_tags(): void
    {
        $expectedFields = [];
        $fields = [];

        foreach (self::$fields as $field) {
            $label = self::$inflector->toSentence(ucfirst($field), uppercaseAll: true);
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <span>%s</span>
                <span>%s</span>
                </div>
                FIELD,
                $label,
                self::$detail[$field]
            );
            $fields[] = sprintf(
                "{dataField '%s', %s, %s, %s}",
                $field,
                'label: \'' . $label . '\'',
                'labelTag: \'span\'',
                'valueTag: \'span\''
            );
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }

    #[Test]
    public function data_field_with_label_and_value_tags_and_attributes(): void
    {
        $expectedFields = [];
        $fields = [];

        foreach (self::$fields as $field) {
            $label = self::$inflector->toSentence(ucfirst($field), uppercaseAll: true);
            $expectedFields[] = sprintf(
                <<<'FIELD'
                <div>
                <span class="label">%s</span>
                <span class="value">%s</span>
                </div>
                FIELD,
                $label,
                self::$detail[$field]
            );
            $fields[] = sprintf(
                "{dataField '%s', %s, %s, %s, %s, %s}",
                $field,
                'label: \'' . $label . '\'',
                'labelAttributes: ["class" => "label"]',
                'labelTag: \'span\'',
                'valueAttributes: ["class" => "value"]',
                'valueTag: \'span\''
            );
        }

        $this->assert(
            self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . __METHOD__ . '.latte',
            sprintf(self::TEMPLATE, implode(PHP_EOL, $fields)),
            [
                'data' => self::$detail
            ],
            sprintf(self::EXPECTED, implode(PHP_EOL, $expectedFields))
        );
    }
}