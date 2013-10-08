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

namespace Tr8n\Cache\Generators;

use DateTime;
use Tr8n\Application;
use Tr8n\Config;
use Tr8n\Language;

abstract class Base extends \Tr8n\Base {

    protected $cache_path;
    protected $started_at;

    public abstract function run();
    abstract function cache($key, $data);
    abstract function symlinkPath();

    function log($msg) {
        $date = new DateTime();
        print($date->format('Y:m:d H:i:s') . ": " . $msg . "\n");
    }

    function generateSymlink() {
        unlink($this->symlinkPath());
        symlink($this->cache_path, $this->symlinkPath());
    }

    /**
     * Caches application data
     */
    function cacheApplication() {
        $this->log("Downloading application...");
        $app = Config::instance()->application->get("application", array("definition" => "true"));
        $key = Application::cacheKey($app["key"]);
        $this->cache($key, json_encode($app));
        $this->log("Application has been cached.");
        return $app;
    }

    /**
     * Caches application languages with full definition
     */
    function cacheLanguages() {
        $this->log("Downloading languages...");
        $count = 0;
        $languages = Config::instance()->application->get("application/languages", array("definition" => "true"));
        foreach ($languages as $lang) {
            $key = Language::cacheKey($lang["locale"]);
            $this->cache($key, json_encode($lang));
            $count += 1;
        }

        $this->log("$count languages have been cached.");
        return $languages;
    }


    function finalize() {
        $this->log("Cache has been created in: " . $this->cache_path);

        $finished_at = new DateTime();
        $since_start = $this->started_at->diff($finished_at);
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;

        if ($minutes > 0)
            $this->log("Cache generation took " . $minutes .  " minutes");
        else
            $this->log("Cache generation took " . $since_start->s . " seconds");

        $this->log("Done.");
    }

}
