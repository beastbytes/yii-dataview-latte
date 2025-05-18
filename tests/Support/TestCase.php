<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Support;

use BeastBytes\View\Latte\LatteFactory;
use BeastBytes\Yii\DataView\Latte\DataViewExtension;
use Latte\Engine;
use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\BeforeClass;
use Yiisoft\Files\FileHelper;
use Yiisoft\Strings\Inflector;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected const CACHE_DIR = __DIR__ . '/../generated/cache';
    protected const TEMPLATE_DIR = __DIR__ . '/../generated/template';
    private const DATA_DIR = __DIR__ . '/data';
    private const DATA_FILE = 'data.php';

    protected static array $data = [];
    protected static array $fields = [];
    protected static Inflector $inflector;
    protected static Engine $latte;

    #[BeforeClass]
    public static function beforeClass(): void
    {
        FileHelper::ensureDirectory(self::CACHE_DIR);
        FileHelper::ensureDirectory(self::TEMPLATE_DIR);

        self::$data = require self::DATA_DIR . DIRECTORY_SEPARATOR . self::DATA_FILE;
        self::$fields = array_keys(self::$data[0]);
        self::$inflector = new Inflector();
        self::$latte = (new LatteFactory(
            cacheDir: self::CACHE_DIR,
            extensions: [new DataViewExtension()]
        ))
            ->create()
        ;
    }

    #[AfterClass]
    public static function afterClass(): void
    {
        FileHelper::removeDirectory(self::CACHE_DIR);
        FileHelper::removeDirectory(self::TEMPLATE_DIR);
    }
}