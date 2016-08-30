<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace senky\userstatistics\tests\mock;

class phpbb_cache_memory extends \phpbb\cache\driver\memory
{
	protected $data = array();

	/**
	* Set cache path
	*/
	function __construct()
	{
	}

	/**
	* Fetch an item from the cache
	*
	* @access protected
	* @param string $var Cache key
	* @return mixed Cached data
	*/
	function _read($var)
	{
		return isset($this->data[$var]) ? $this->data[$var] : false;
	}

	/**
	* Store data in the cache
	*
	* @access protected
	* @param string $var Cache key
	* @param mixed $data Data to store
	* @param int $ttl Time-to-live of cached data
	* @return bool True if the operation succeeded
	*/
	function _write($var, $data, $ttl = 2592000)
	{
		$this->data[$var] = $data;
		return true;
	}

	/**
	* Remove an item from the cache
	*
	* @access protected
	* @param string $var Cache key
	* @return bool True if the operation succeeded
	*/
	function _delete($var)
	{
		unset($this->data[$var]);
		return true;
	}

	public function obtain_ranks()
	{
		return array(
			'normal' 	=> array(),
			'special'	=> array(
				1	=> array(
					'rank_id'		=> 1,
					'rank_title'	=> 'Site Admin',
					'rank_special'	=> 1,
					'rank_image'	=> '',
				),
			),
		);
	}
}
