<?php
/*
	Question2Answer by Gideon Greenspan and contributors
	http://www.question2answer.org/

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

namespace Q2A\Storage;

/**
 * Caches data (typically from database queries) to the filesystem.
 */
class CacheFactory
{
	private static $cacheDriver = null;

	/**
	 * Get the appropriate cache handler.
	 * @return CacheDriver The cache handler.
	 */
	public static function getCacheDriver()
	{
		if (self::$cacheDriver === null) {
			$config = array(
				'enabled' => (int) qa_opt('caching_enabled') === 1,
				'keyprefix' => QA_FINAL_MYSQL_DATABASE . '.' . QA_MYSQL_TABLE_PREFIX . '.',
				'dir' => defined('QA_CACHE_DIRECTORY') ? QA_CACHE_DIRECTORY : null,
			);

			$driver = qa_opt('caching_driver');

			switch($driver)
			{
				case 'memcached':
					self::$cacheDriver = new MemcachedDriver($config);
					break;

				case 'filesystem':
				default:
					self::$cacheDriver = new FileCacheDriver($config);
					break;
			}

		}

		return self::$cacheDriver;
	}
}
