<?php

namespace One234ru;

class Arrays
{
    public static function appendItemUsingKey(
        array &$array,
        array $item,
        string $field_to_use_as
    ) :void
    {
        $key = $item[$field_to_use_as];
        $array[$key] = $item;
    }

    public static function arrayColumnPreserveKeys($array, $field_name)
    {
        $keys = array_column($array, $field_name);
        // array_column() resets indexes, so:
        $values = array_values($array);
        $map = array_combine($keys, $values);
        return $map;
    }

    /**
     * If the value should be absent in the matching item,
     * false may be passed as a corresponding parameter.
     * In the future strict mode it will have to be null instead.
     */
    public static function filterByFields(
        $array,
        $fields,
        $return_single_item = false
    ) :array|false {
        $callback = function($item) use ($fields) {
            foreach ($fields as $key => $value) {
                if (!isset($item[$key])) {
                    if (!$value) {
                        continue;
                    } else {
                        return false;
                    }
                }
                if ($item[$key] != $value) {
                    return false;
                }
            }
            return true;
        };
        $filtered = array_filter($array, $callback);
        if ($return_single_item) {
            $item = reset($filtered);
            return $item;
        } else {
            return $filtered;
        }
    }

    public static function filterRecursive($array, $callback = null)
    {
        $fn = __FUNCTION__;
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::$fn($value, $callback);
            }
        }
        return array_filter($array, $callback);
    }

    public static function keepKeys($array, $keys)
    {
        return array_intersect_key(
            $array,
            array_fill_keys($keys, true)
        );
    }

}