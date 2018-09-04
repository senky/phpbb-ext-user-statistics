<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
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

	/** @var \phpbb\config\config */
	protected $config;

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
	 * @param \phpbb\config\config					$config		Config object
	 * @param string								$root_path	phpbb root path
	 * @param string								$php_ext	php ext
	 * @access public
	 */
	public function __construct(\phpbb\cache\driver\driver_interface $cache, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\config\config $config, $root_path, $php_ext)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->config = $config;
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
					WHERE topic_poster = ' . $this->user->data['user_id'] . '
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
				'S_IS_31'		=> version_compare($this->config['version'], '3.2.0', '<'),
				'US_IP'			=> $this->user->data['user_ip'],
				'US_REGDATE'	=> $this->user->format_date($this->user->data['user_regdate']),
				'US_ID'			=> $this->user->data['user_id'],
				'US_POSTS'		=> $this->user->data['user_posts'],
				'U_US_POSTS'	=> append_sid("{$this->root_path}search.{$this->php_ext}", 'search_id=egosearch&amp;sr=posts'),
				'US_RTITLE'		=> ($user_rank['title'] != '') ? $user_rank['title'] : $this->user->lang('US_NO_RANK'),
				'US_TOPICS'		=> $user_topics,
				'U_US_TOPICS'	=> append_sid("{$this->root_path}search.{$this->php_ext}", 'search_id=egosearch'),
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
}
