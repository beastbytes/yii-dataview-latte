<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests;

use BeastBytes\View\Latte\LatteFactory;
use BeastBytes\Yii\DataView\Latte\DataViewExtension;
use Latte\Engine;
use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Data\Reader\ReadableDataInterface;
use Yiisoft\Files\FileHelper;
use Yiisoft\Strings\Inflector;

abstract class TestBase extends TestCase
{
    protected const CACHE_DIR = __DIR__ . '/generated/cache';
    protected const TEMPLATE_DIR = __DIR__ . '/generated/template';

    protected static array $data = [];
    protected static array $fields = [];
    protected static Inflector $inflector;
    protected static Engine $latte;

    #[BeforeClass]
    public static function beforeClass(): void
    {
        FileHelper::ensureDirectory(self::CACHE_DIR);
        FileHelper::ensureDirectory(self::TEMPLATE_DIR);

        self::$data = require __DIR__ . '/resources/data.php';
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