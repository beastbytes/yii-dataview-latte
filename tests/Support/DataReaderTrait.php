<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Support;

use PHPUnit\Framework\Attributes\BeforeClass;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Data\Reader\ReadableDataInterface;

trait DataReaderTrait
{
    protected static ReadableDataInterface $dataReader;

    #[BeforeClass]
    public static function before(): void
    {
        self::$dataReader = (new OffsetPaginator(new IterableDataReader(self::$data)))
            ->withPageSize(10)
        ;
    }
}