<?php

/**
 * ag = Array Get. Alias to sso() function
 * @inheritdoc
 */
function ag($source, $keys, $default = null) {
    return sso($source, $keys, $default);
}

/**
 * sso = Safe Subscription Operator.
 * It doesn't return any exceptions and warnings if any of keys don't exist in the $source array.
 * It will return $default value instead.
 * `sso($array, ['a', 'b', 'c'])` is equivalent to `$array['a']['b']['c']`
 * @param $source array Array from which to get values
 * @param $keys array|mixed if array, then for each value of this array subscription operator will be called if possible
 * If for some key
 * @param null $default
 * @return null
 */
function sso($source, $keys, $default = null) {
    if (!$source) {
        return $default;
    }
    if (!is_array($keys)) {
        $keys = array($keys);
    }
    $result = $source;
    foreach ($keys as $key) {
        if (array_key_exists($key, $result)) {
            $result = $result[$key];
        } else {
            return $default;
        }
    }
    return $result;
}