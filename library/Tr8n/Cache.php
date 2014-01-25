<?php
/**
 * Copyright (c) 2014 Michael Berkovich, http://tr8nhub.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Tr8n;

class Cache {


    /**
     * @return mixed
     */
    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $class = Config::instance()->cacheAdapterClass();
            $inst = new $class();
        }
        return $inst;
    }

    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    public static function fetch($key, $default = null) {
        if (!Config::instance()->isCacheEnabled()) {
            if (is_callable($default)) {
                return $default();
            }
            return $default;
        }
        return self::instance()->fetch($key, $default);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function store($key, $value) {
        if (!Config::instance()->isCacheEnabled()) {
            return false;
        }
        return self::instance()->store($key, $value);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function delete($key) {
        if (!Config::instance()->isCacheEnabled()) {
            return false;
        }
        return self::instance()->delete($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function exists($key) {
        if (!Config::instance()->isCacheEnabled()) {
            return false;
        }
        return self::instance()->exists($key);
    }

    /**
     * @return bool
     */
    public static function isCachedBySource() {
        return self::instance()->isCachedBySource();
    }

    /**
     * @return bool
     */
    public static function isReadOnly() {
        return self::instance()->isReadOnly();
    }

}
