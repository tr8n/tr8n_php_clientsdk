<?php

/**
 * Copyright (c) 2013 Michael Berkovich, tr8nhub.com
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

namespace Tr8n\Cache;

use Tr8n\Logger;
use Tr8n\Application;
use Tr8n\Component;
use Tr8n\Config;
use Tr8n\Language;
use Tr8n\Source;

abstract class Base {

    public abstract function fetch($key, $default = null);
    public abstract function store($key, $value);
    public abstract function delete($key);
    public abstract function exists($key);

    /**
     * @return bool
     */
    public function isCachedBySource() {
        return true;
    }

    /**
     * @return bool
     */
    public function isReadOnly() {
        return true;
    }

    /**
     * @param string $msg
     */
    function warn($msg) {
        Logger::instance()->warn($msg);
    }

    /**
     * @param string $msg
     */
    function info($msg) {
        Logger::instance()->info($msg);
    }

    function versionedKey($key) {
        return Config::instance()->cacheVersion() . "_" . $key;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    function serializeObject($key, $data) {
        $prefix = substr($key, 0, 2);
        if (in_array($prefix, array('a@', 'l@', 's@', 'c@'))) {
            $json = json_encode($data->toArray());
//            $this->info($json);
            return $json;
        }

        return $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return Application|Component|Language|Source
     */
    function deserializeObject($key, $data) {
        $prefix = substr($key, 0, 2);

//        $this->info($data);

        switch($prefix) {
            case 'a@': return new Application(json_decode($data, true));
            case 'l@': return new Language(array_merge(json_decode($data, true), array("application" => Config::instance()->application)));
            case 's@': return new Source(array_merge(json_decode($data, true), array("application" => Config::instance()->application)));
            case 'c@': return new Component(array_merge(json_decode($data, true), array("application" => Config::instance()->application)));
        }

        return $data;
    }

}
