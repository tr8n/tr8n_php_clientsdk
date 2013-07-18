<?php

#--
# Copyright (c) 2010-2013 Michael Berkovich, tr8nhub.com
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#++

namespace Tr8n;

class Cache {

    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $class = \Tr8n\Config::instance()->cacheAdapterClass();
            $inst = new $class();
        }
        return $inst;
    }

    public static function fetch($key, $default = null) {
        if (!\Tr8n\Config::instance()->isCachingEnabled()) {
            if (is_callable($default)) {
                return $default();
            }
            return $default;
        }
        return self::instance()->fetch($key, $default);
    }

    public static function store($key, $value) {
        if (!\Tr8n\Config::instance()->isCachingEnabled()) {
            return false;
        }
        return self::instance()->store($key, $value);
    }

    public static function delete($key) {
        if (!\Tr8n\Config::instance()->isCachingEnabled()) {
            return false;
        }
        return self::instance()->delete($key);
    }

    public static function exists($key) {
        if (!\Tr8n\Config::instance()->isCachingEnabled()) {
            return false;
        }
        return self::instance()->exists($key);
    }

}
