<?php

declare(strict_types=1);

namespace BeastBytes\Yii\DataView\Latte\Tests\Support;

use FastRoute\RouteParser;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\SimpleMessageFormatter;
use Yiisoft\Translator\Translator;
use Yiisoft\Translator\TranslatorInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class Mock extends TestCase
{
    public static function category(string $category, string $path): CategorySource
    {
        $messageSource = new MessageSource($path);

        return new CategorySource($category, $messageSource, new SimpleMessageFormatter());
    }

    public static function translator(
        string $locale,
        ?string $fallbackLocale = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ): TranslatorInterface {
        return new Translator($locale, $fallbackLocale, 'app', $eventDispatcher);
    }

    /**
     * @psalm-param array<Route> $routes
     */
    public static function urlGenerator(
        array $routes = [],
        ?CurrentRoute $currentRoute = null,
        ?RouteParser $parser = null
    ): UrlGeneratorInterface {
        if ($routes === []) {
            $routes = [
                Route::get('/admin/record')->name('admin/record'),
                Route::get('/admin/record/delete')->name('admin/record/delete'),
                Route::get('/admin/record/update')->name('admin/record/update'),
                Route::get('/admin/record/view')->name('admin/record/view'),
            ];
        }

        $routeCollection = self::routeCollection($routes);

        return new UrlGenerator($routeCollection, $currentRoute, $parser);
    }

    /**
     * @psalm-param array<Route> $routes
     */
    private static function routeCollection(array $routes): RouteCollectionInterface
    {
        $collector = new RouteCollector();
        $collector->addRoute(...$routes);

        return new RouteCollection($collector);
    }
}