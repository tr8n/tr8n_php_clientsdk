<?php

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
	
	// Save data into Cache
	public function save($key, $data, $ttl = 3600) {
		if (get_class($this->_memcached) == 'Memcached') {
			return $this->_memcached->set($key, array($data, time(), $ttl), $ttl);
		} else if (get_class($this->_memcached) == 'Memcache') {
			return $this->_memcached->set($key, array($data, time(), $ttl), 0, $ttl);
		}
	
		return false;
	}
	
	// Fetch data from Cache
	public function get($key) {
		$data = $this->_memcached->get($key);
		return (is_array($data)) ? $data[0] : false;
	}
	
	// Detele data from Cache
	public function delete($key) {
		return $this->_memcached->delete($key);
	}
	
	//  // clean will marks all the items as expired, so occupied memory will be overwritten by new items.
	public function clean() {
		return $this->_memcached->flush();
	}
	
}

?>