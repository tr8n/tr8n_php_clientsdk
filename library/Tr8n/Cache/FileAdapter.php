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

use Tr8n\Application;
use Tr8n\Config;
use Tr8n\Logger;

class FileAdapter extends Base {

    public function fetch($key, $default = null) {
        $file_path = self::filePath($key);

        if (file_exists($file_path)) {
            $this->info("Cache hit " . $key);
            $value = file_get_contents($file_path);
            return $this->deserializeObject($key, $value);
        }

        $this->info("Cache miss " . $key);

        if ($default == null)
            return null;

        if (is_callable($default)) {
            $value = $default();
        } else {
            $value = $default;
        }

        return $value;
    }

    public static function cachePath() {
        return Config::instance()->cachePath() . 'files/current';
    }

    public static function filePath($key) {
        return self::cachePath() . '/' . self::fileName($key);
    }

    public static function fileName($key) {
        return preg_replace('/[\.\/]/', '-', $key) . '.json';
    }

    public function store($key, $value) {
        $this->warn("This is a readonly cache");
    }

    public function delete($key) {
        $this->warn("This is a readonly cache");
        return null;
    }

    public function exists($key) {

    }

}

?>