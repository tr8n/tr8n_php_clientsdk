<?php

#--
# Copyright (c) 2013 Michael Berkovich, tr8nhub.com
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

namespace Tr8n\Cache;

use Tr8n\Logger;

class ChdbAdapter extends Base {

    private $chdb;

    function __construct() {
        $this->chdb = new \chdb(\Tr8n\Config::instance()->chdbPath());
    }

    public function fetch($key, $default = null) {
        $value = $this->chdb->get($key);
        if ($value) {
            Logger::instance()->info("Cache hit " . $key);
            return $this->constructObject($key, $value);;
        }

        Logger::instance()->info("Cache miss " . $key);

        if ($default == null)
            return null;

        if (is_callable($default)) {
            $value = $default();
        } else {
            $value = $default;
        }

        return $value;
    }

    private function constructObject($key, $data) {
        if (substr($key, 0, 2) == 't@') {
            if (strstr($data, '},{') === false) {
                return new \Tr8n\Translation(array("label" => $data));
            }

            $translations_json = json_decode($data, true);
            $translations = array();
            foreach($translations_json as $json) {
                $t =  new \Tr8n\Translation(array("label" => $json["label"]));
                if (isset($json["context"]))
                    $t->context = $json["context"];
                array_push($translations, $t);
            }
            return $translations;
        }

        if (substr($key, 0, 2) == 'a@') {
//            Logger::instance()->info("Constructing application", $data);
            return new \Tr8n\Application(json_decode($data, true));
        }

        if (substr($key, 0, 2) == 'l@') {
            return new \Tr8n\Language(json_decode($data, true));
        }

        if (substr($key, 0, 2) == 'c@') {
            return new \Tr8n\Component(json_decode($data, true));
        }

        if (substr($key, 0, 2) == 's@') {
            return new \Tr8n\Source(json_decode($data, true));
        }

        return $data;
    }

    public function store($key, $value) {
        throw new \Tr8n\Tr8nException("Chdb is a readonly cache");
    }

    public function delete($key) {
        throw new \Tr8n\Tr8nException("Chdb is a readonly cache");
    }

    public function exists($key) {
        $value = $this->chdb->get($key);
        return ($value!=null);
    }

}
