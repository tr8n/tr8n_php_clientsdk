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

use Zend\Cache\Storage\Adapter\Memcached;

class MemcacheAdapter extends Base {
	
	private $_memcached;
	protected $_memcached_conf = array(
			'default_host' => '127.0.0.1',
			'default_port' => 11211,
			'default_weight' => 1,
	);

	public function __construct() {
		parent::__construct ();
		$this->_memcached = new Memcached();
		$this->_memcached->connect($this->_memcached_conf['default_host'], $this->_memcached_conf['default_port']) or die ("Could not connect");
	}

//	// Save data into Cache
//	public function save($key, $data, $ttl = 3600) {
//		if (get_class($this->_memcached) == 'Memcached') {
//			return $this->_memcached->set($key, array($data, time(), $ttl), $ttl);
//		} else if (get_class($this->_memcached) == 'Memcache') {
//			return $this->_memcached->set($key, array($data, time(), $ttl), 0, $ttl);
//		}
//
//		return false;
//	}
//
//	// Fetch data from Cache
//	public function get($key) {
//		$data = $this->_memcached->get($key);
//		return (is_array($data)) ? $data[0] : false;
//	}
//
//
//	//  // clean will marks all the items as expired, so occupied memory will be overwritten by new items.
//	public function clean() {
//		return $this->_memcached->flush();
//	}

    public function fetch($key, $default = null) {
        $success = false;
        $data = apc_fetch($key, $success);
        if ($success === TRUE) return $data;

        if ($default === null)
            return null;

        if (is_callable($default)) {
            $value = $default();
        } else {
            $value = $default;
        }
        apc_store($key, $value, 3600);

        return $value;
    }

    public function store($key, $value) {

    }

    public function delete($key) {
		return $this->_memcached->delete($key);
    }

    public function exists($key) {
        return apc_exists($key);
    }


}

?>