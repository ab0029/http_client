<?php

namespace Young\HttpClient;

use Closure;

class Application
{
    use Traits\MockTrait;
    use Traits\HistoryTrait;
    use Traits\LogTrait;
    use Traits\UserAgentTrait;

    protected static $customCreators = [];

    const VERSION = '1.0.0';

    public static function make(string $product, $config = [])
    {
        if (isset(static::$customCreators[$product])) {
            return static::callCustomCreator($product, $config);
        }

        $product = \ucfirst($product);

        $product_class = '\\Young\\HttpClient\\Clients\\' . $product . '\\Application';

        if (\class_exists($product_class)) {
            return new $product_class($config);
        }

        throw new Exceptions\ClientException(
            "May not yet support product $product quick access",
            SDK::SERVICE_NOT_FOUND
        );
    }

    protected static function callCustomCreator($product, $config)
    {
        return static::$customCreators[$product]($config);
    }

    /**
     * Register a custom product creator Closure.
     *
     * @param  string  $product
     * @param  \Closure  $callback
     * @return $this
     */
    public static function extend($product, Closure $callback)
    {
        static::$customCreators[$product] = $callback;
    }

    public static function __callStatic($product, $arguments)
    {
        return self::make($product, ...$arguments);
    }
}