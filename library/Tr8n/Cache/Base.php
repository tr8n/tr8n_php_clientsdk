<?php

/**
 * Copyright (c) 2014 Michael Berkovich, TranslationExchange.com
 *
 *  _______                  _       _   _             ______          _
 * |__   __|                | |     | | (_)           |  ____|        | |
 *    | |_ __ __ _ _ __  ___| | __ _| |_ _  ___  _ __ | |__  __  _____| |__   __ _ _ __   __ _  ___
 *    | | '__/ _` | '_ \/ __| |/ _` | __| |/ _ \| '_ \|  __| \ \/ / __| '_ \ / _` | '_ \ / _` |/ _ \
 *    | | | | (_| | | | \__ \ | (_| | |_| | (_) | | | | |____ >  < (__| | | | (_| | | | | (_| |  __/
 *    |_|_|  \__,_|_| |_|___/_|\__,_|\__|_|\___/|_| |_|______/_/\_\___|_| |_|\__,_|_| |_|\__, |\___|
 *                                                                                        __/ |
 *                                                                                       |___/
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

    const TR8N_VERSION_KEY = '__tr8n_version__';
    const TR8N_KEY_PREFIX = 'tr8n_v';

    /**
     * @var string
     */
    private $base_cache_path;

    /**
     * Holds the current cache version
     *
     * @var string
     */
    private $version;

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
        return false;
    }

    /**
     * @return string
     */
    public function key() {
        $parts = explode('\\', get_class($this));
        return $parts[2];
    }

    /**
     * @param string $msg
     */
    function warn($msg) {
        Logger::instance()->warn($this->key() . " - " . $msg);
    }

    /**
     * @param string $msg
     */
    function info($msg) {
        Logger::instance()->info($this->key() . " - " . $msg);
    }

    /**
     * Returns current cache version
     *
     * @return integer
     */
    function version() {
        if ($this->version == null) {
            $this->version = intval($this->fetch(self::TR8N_VERSION_KEY, Config::instance()->configValue("cache.version", 1)));
        }
        return $this->version;
    }

    /**
     * Increments cache version
     * @return integer
     */
    function incrementVersion() {
        $this->store(self::TR8N_VERSION_KEY, $this->version() + 1);
        return $this->version();
    }

    /**
     * Appends version to a key
     *
     * @param string $key
     * @return string
     */
    function versionedKey($key) {
        if ($key == self::TR8N_VERSION_KEY) return $key;
        return self::TR8N_KEY_PREFIX . $this->version() . "_" . $key;
    }

    /**
     * @return string
     */
    function baseCachePath() {
        if ($this->base_cache_path == null) {
            $this->base_cache_path = Config::instance()->configValue("cache.path", Config::instance()->rootPath() . DIRECTORY_SEPARATOR . "cache");
            if (!file_exists($this->base_cache_path)) mkdir($this->base_cache_path, 0777, true);
            $this->info("Base cache path: " . $this->base_cache_path);
        }
        return $this->base_cache_path;
    }

    /**
     * @return string
     */
    function currentCachePath() {
        return $this->baseCachePath() . DIRECTORY_SEPARATOR . 'current';
    }
}
