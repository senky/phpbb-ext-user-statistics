<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2019 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace senky\userstatistics\migrations;

class m1_add_user_stats_location extends \phpbb\db\migration\migration
{
	/**
	 * {@inheritDoc}
	 */
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_stats_location');
	}

	/**
	 * Add table columns schema to the database:
	 *    users:
	 *        user_stats_location
	 *
	 * @return array Array of table columns schema
	 * @access public
	 */
	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'users'	=> [
					'user_stats_location'	=> ['BOOL', 1],
				],
			],
		];
	}

	/**
	 * Drop table columns schema from the database
	 *
	 * @return array Array of table columns schema
	 * @access public
	 */
	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'users'	=> [
					'user_stats_location',
				],
			],
		];
	}
}
