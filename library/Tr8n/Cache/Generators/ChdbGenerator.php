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
use Tr8n\Cache\ChdbAdapter;
use Tr8n\Config;
use Tr8n\Language;

class ChdbGenerator extends Base {

    /**
     * @var mixed[]
     */
    public $cache;

    /**
     * @var array[]
     */
    public $translations;

    /**
     * @var string
     */
    private $chdb_path;

    /**
     * @return ChdbGenerator
     */
    public static function instance() {
        static $inst = null;
        if ($inst === null) {
            $inst = new ChdbGenerator();
        }
        return $inst;
    }

    /**
     *
     */
    public function run() {
        $this->started_at = new \DateTime();
        $this->cache_path = Config::instance()->cachePath() . '/chdb/';

        $this->cache = array();
        $this->cacheApplication();
        $this->cacheLanguages();
        $this->cacheTranslations();

        $this->generateChdb();

        $this->cache_path = $this->chdb_path;

        $this->generateSymlink();
        $this->finalize();
    }

    /**
     * @param string|array $key
     * @param string|null $data
     */
    public function cache($key, $data = null) {
        if (is_array($key)) {
            $this->cache = array_merge($this->cache, $key);
        } else {
            $this->cache[$key] = $data;
        }
    }

    /**
     * Caches translation keys
     */
    private function cacheTranslations() {
        $this->log("Downloading translations...");

        stream_wrapper_register("chdb", '\Tr8n\Cache\Generators\ChdbStream') or die("Failed to register Chdb protocol for streaming Tr8n translation keys");
        $fp = fopen("chdb://ChdbInMemory", "r+");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Config::instance()->application->host . Application::API_PATH . "application/translations?stream=true&client_id=" . Config::instance()->application->key);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 256);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    /**
     * Generates chdb database
     */
    public function generateChdb() {
        $this->chdb_path = $this->cache_path . 'tr8n_' . Config::instance()->application->key . '_' . count($this->translations) . '_@_' . $this->started_at->format('Y_m_d_H_i_s') . '.chdb';
        $this->extracted_at = new \DateTime();

        $this->log("Writing chdb file...");
        $this->log("File: " . $this->chdb_path);

        $success = chdb_create($this->chdb_path, $this->cache);

        if (!$success) {
            fprintf(STDERR, "Failed to create chdb file $this->chdb_path\n");
            return;
        }
    }

    /**
     * @return string
     */
    function symlinkPath() {
        return ChdbAdapter::chdbPath();
    }

}
