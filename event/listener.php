<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2019 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace senky\userstatistics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\cache\driver\driver_interface	$cache		Cache driver interface
	 * @param \phpbb\db\driver\driver_interface		$db			Database driver
	 * @param \phpbb\template\template				$template	Template object
	 * @param \phpbb\user							$user		User object
	 * @param \phpbb\request\request				$request	Request object
	 * @param string								$root_path	phpbb root path
	 * @param string								$php_ext	php ext
	 * @access public
	 */
	public function __construct(\phpbb\cache\driver\driver_interface $cache, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, $root_path, $php_ext)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array<string,string>
	 * @static
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'		=> 'set_template_variables',
			'core.submit_post_end'				=> 'clear_cache',
			'core.delete_topics_after_query'	=> 'clear_cache',
			'core.set_topic_visibility_after'	=> 'clear_cache',

			'core.ucp_prefs_personal_data'			=> 'setting_data',
			'core.ucp_prefs_personal_update_data'	=> 'setting_update',
		);
	}

	/**
	 * Set required template variables
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function set_template_variables($event)
	{
		if ($this->user->data['is_registered'])
		{
			$this->user->add_lang_ext('senky/userstatistics', 'user_statistics');

			// topics count
			$sql = 'SELECT COUNT(topic_poster) as user_topics
					FROM ' . TOPICS_TABLE . '
					WHERE topic_poster = ' . (int) $this->user->data['user_id'] . '
						AND topic_visibility = ' . ITEM_APPROVED;
			$result = $this->db->sql_query($sql, 3600);
			$user_topics = $this->db->sql_fetchfield('user_topics');
			$this->db->sql_freeresult($result);

			if (!function_exists('phpbb_get_user_rank'))
			{
				include($this->root_path . 'includes/functions_display.' . $this->php_ext);
			}
			$user_rank = phpbb_get_user_rank($this->user->data, $this->user->data['user_posts']);

			$this->template->assign_vars(array(
				'US_IP'			=> $this->user->data['user_ip'],
				'US_REGDATE'	=> $this->user->format_date($this->user->data['user_regdate']),
				'US_ID'			=> $this->user->data['user_id'],
				'US_POSTS'		=> $this->user->data['user_posts'],
				'U_US_POSTS'	=> append_sid("{$this->root_path}search.{$this->php_ext}", 'search_id=egosearch&amp;sr=posts'),
				'US_RTITLE'		=> ($user_rank['title'] != '') ? $user_rank['title'] : $this->user->lang('US_NO_RANK'),
				'US_TOPICS'		=> $user_topics,
				'U_US_TOPICS'	=> append_sid("{$this->root_path}search.{$this->php_ext}", 'search_id=egosearch'),
				'US_LOCATION'	=> $this->user->data['user_stats_location'],
			));
		}
	}

	/**
	 * Clear cache for user topics count
	 *
	 * @return null
	 * @access public
	 */
	public function clear_cache()
	{
		// sadly, this destroys all cache items for topics table, but phpBB doesn't provide cleaner way
		$this->cache->destroy('sql', TOPICS_TABLE);
	}

	/**
	 * Set user statistics location
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function setting_data($event)
	{
		$this->user->add_lang_ext('senky/userstatistics', 'ucp');

		$data = $event['data'];
		$data['user_stats_location'] = $this->request->variable('user_stats_location', (bool) $this->user->data['user_stats_location']);
		$event['data'] = $data;

		$this->template->assign_var('S_USER_STATISTICS_LOCATION', $data['user_stats_location']);
	}

	/**
	 * Update user statistics location
	 *
	 * @param object $event The event object
	 * @return null
	 * @access public
	 */
	public function setting_update($event)
	{
		$sql_ary = $event['sql_ary'];
		$sql_ary['user_stats_location'] = $event['data']['user_stats_location'];
		$event['sql_ary'] = $sql_ary;
	}
}
