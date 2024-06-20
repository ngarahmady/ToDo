<?php
namespace App\Enums;

use ReflectionClass;

/**
 * Class Enum.
 */
abstract class Enum
{


    /**
     * Get list of available items.
     *
     * @return array
     */
    public static function all(): array
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    /**
     *
     * Get the item title.
     *
     * @param $index
     * @return array|null
     */
    public static function value($index): ?array
    {
        $reflection = new ReflectionClass(static::class);
        $list = $reflection->getConstants();

        return $list[$index] ?? null;
    }

    /**
     *
     * Get the item key.
     *
     * @param $value
     * @return int|string|null
     */
    public static function key($value): int|string|null
    {
        $reflection = new ReflectionClass(static::class);
        $list = array_flip($reflection->getConstants());

        return $list[$value] ?? null;
    }

    /**
     * Return a random item in Enum.
     *
     * @return string|array|int
     */
    public static function random(): string|array|int
    {
        $reflection = new ReflectionClass(static::class);
        $options = array_flip($reflection->getConstants());

        return array_rand($options);
    }

    /**
     *
     * Get the item title.
     *
     * @return array
     */
    public static function values(): array
    {
        $reflection = new ReflectionClass(static::class);
        $list = $reflection->getConstants();

        return array_values($list) ?? [];
    }
}
