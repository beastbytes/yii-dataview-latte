<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Support;

use PHPUnit\Framework\Attributes\Before;
use Yiisoft\Data\Paginator\KeysetPaginator;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Data\Paginator\PageToken;
use Yiisoft\Data\Reader\Iterable\IterableDataReader;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Definitions\Reference;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\Route;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumnRenderer;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Yii\DataView\YiiRouter\ActionColumnUrlCreator;

trait GridViewTestTrait
{
    #[Before]
    protected function setUp(): void
    {
        $container = new Container(ContainerConfig::create()->withDefinitions($this->config()));
        WidgetFactory::initialize($container, [
            GridView::class => [
                'addColumnRendererConfigs()' => [
                    [
                        ActionColumnRenderer::class => [
                            'urlCreator' => Reference::to(ActionColumnUrlCreator::class),
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createOffsetPaginator(
        array $data,
        int $pageSize,
        int $currentPage = 1,
        bool $sort = false
    ): OffsetPaginator {
        $data = new IterableDataReader($data);

        if ($sort) {
            $data = $data->withSort(Sort::any()->withOrder(['id' => 'asc', 'releaseDate' => 'asc']));
        }

        return (new OffsetPaginator($data))->withToken(PageToken::next((string) $currentPage))->withPageSize($pageSize);
    }

    private function createKeysetPaginator(array $data, int $pageSize): KeysetPaginator
    {
        $data = (new IterableDataReader($data))
            ->withSort(Sort::any()->withOrder(['id' => 'asc', 'releaseDate' => 'asc']));

        return (new KeysetPaginator($data))->withPageSize($pageSize);
    }

    private function config(): array
    {
        $currentRoute = new CurrentRoute();
        $currentRoute->setRouteWithArguments(Route::get('/admin/record')->name('admin/record'), []);

        return [
            CurrentRoute::class => $currentRoute,
            UrlGeneratorInterface::class => Mock::urlGenerator([], $currentRoute),
            ValidatorInterface::class => Validator::class,
        ];
    }
}