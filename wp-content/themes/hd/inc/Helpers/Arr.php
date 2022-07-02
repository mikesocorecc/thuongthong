<?php

namespace Webhd\Helpers;

\defined( '\WPINC' ) || die;

class Arr
{
    /**
     * @param array $arr1
     * @param array $arr2
     * @return bool
     */
    public static function compare(array $arr1, array $arr2)
    {
        sort($arr1);
        sort($arr2);
        return $arr1 == $arr2;
    }

    /**
     * @param mixed $value
     * @param mixed $callback
     *
     * @return array
     */
    public static function convertFromString($value, $callback = null)
    {
        if (is_scalar($value)) {
            $value = array_map('trim', explode(',', Cast::toString($value)));
        }
        $callback = if_empty(Cast::toString($callback), 'is_not_empty');
        return static::reindex(array_filter((array)$value, $callback));
    }

    /**
     * @param mixed $array
     * @return array
     */
    public static function reindex($array)
    {
        return static::isIndexedAndFlat($array) ? array_values($array) : $array;
    }

    /**
     * @param mixed $array
     * @return bool
     */
    public static function isIndexedAndFlat($array)
    {
        if (!is_array($array) || array_filter($array, 'is_array')) {
            return false;
        }
        return wp_is_numeric_array($array);
    }

    /**
     * @param string|int $key
     * @param array $array
     * @param array $insert
     *
     * @return array
     */
    public static function insertAfter($key, array $array, array $insert)
    {
        return static::insert($array, $insert, $key, 'after');
    }

    /**
     * @param string|int $key
     * @param array $array
     * @param array $insert
     *
     * @return array
     */
    public static function insertBefore($key, array $array, array $insert)
    {
        return static::insert($array, $insert, $key, 'before');
    }

    /**
     * @param string|int $key
     * @param string $position
     * @return array
     */
    public static function insert($array, array $insert, $key, $position = 'before')
    {
        $keyPosition = array_search($key, array_keys($array));
        if (false !== $keyPosition) {
            $keyPosition = Cast::toInt($keyPosition);
            if ('after' == $position) {
                ++$keyPosition;
            }
            $result = array_slice($array, 0, $keyPosition);
            $result = array_merge($result, $insert);
            return array_merge($result, array_slice($array, $keyPosition));
        }
        return array_merge($array, $insert);
    }

    /**
     * @param array $values
     * @param string $prefix
     * @param mixed $prefixed
     *
     * @return array
     */
    public static function prefixKeys($values, string $prefix = '_', $prefixed = true)
    {
        $trim = (true === $prefixed) ? $prefix : '';
        $prefixed = [];
        foreach ($values as $key => $value) {
            $key = trim($key);
            if (0 === strpos($key, $prefix)) {
                $key = substr($key, strlen($prefix));
            }
            $prefixed[$trim . $key] = $value;
        }
        return $prefixed;
    }

    /**
     * @param array $values
     * @param string $prefix
     *
     * @return array
     */
    public static function unprefixKeys(array $values, string $prefix = '_')
    {
        return static::prefixKeys($values, $prefix, false);
    }

    /**
     * @param array $array
     * @param mixed $value
     * @param mixed $key
     *
     * @return array
     */
    public static function prepend(&$array, $value, $key = null)
    {
        if (!is_null($key)) {
            return $array = [$key => $value] + $array;
        }
        array_unshift($array, $value);
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function removeEmptyValues(array $array = [])
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_empty($value)) {
                continue;
            }
            $result[$key] = if_true(!is_array($value), $value, function () use ($value) {
                return static::removeEmptyValues($value);
            });
        }
        return $result;
    }
}