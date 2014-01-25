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

namespace Tr8n\Cache\Generators;

use Tr8n\Application;
use Tr8n\Cache\FileAdapter;
use Tr8n\Config;
use Tr8n\Language;
use Tr8n\Source;

class FileGenerator extends Base {

    /**
     * @var array[]
     */
    private $languages;

    /**
     * @return FileGenerator
     */
    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new FileGenerator();
        }
        return $inst;
    }

    /**
     *
     */
    public function run() {
        $this->started_at = new \DateTime();

        $this->prepareCache();

        $this->cacheApplication();
        $this->languages = $this->cacheLanguages();
        $this->cacheTranslations();

        $this->generateSymlink();
        $this->finalize();
    }

    /**
     * Creates cache folder for current cache export
     */
    function prepareCache() {
        $this->cache_path = Config::instance()->cachePath() . '/files/' . 'tr8n_' . Config::instance()->application->key . '_' . $this->started_at->format('Y_m_d_H_i_s') . '/';
        mkdir($this->cache_path, 0777, true);
    }

    /**
     * @param string|array $key
     * @param string|null $data
     */
    public function cache($key, $data) {
        $file_name = $this->cache_path . FileAdapter::fileName($key);
        file_put_contents($file_name, $data);
    }

    function symlinkPath() {
        return FileAdapter::cachePath();
    }

    /**
     * Caches translations
     */
    private function cacheTranslations() {
        $this->log("Downloading translations...");
        $sources = Config::instance()->application->get("application/sources");
        foreach($this->languages as $language) {
            $this->log("--------------------------------------------------------------");
            $this->log("Downloading ". $language["locale"]. " language...");
            $this->log("--------------------------------------------------------------");
            foreach($sources as $source) {
                $this->log("Downloading ". $source["source"] . " in " . $language["locale"]. "...");
                $key = Source::cacheKey($source["source"], $language["locale"]);
                $translation_keys = Config::instance()->application->get("source/translations", array("source" => $source["source"], "locale" => $language["locale"]));
                $source = array("source" => $source["source"], "translation_keys" => $translation_keys);
                $this->cache($key, json_encode($source));
            }
        }
    }

}
