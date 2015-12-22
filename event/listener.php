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

	/**
	 * Constructor
	 *
	 * @param \phpbb\cache\driver\driver_interface	$cache		Cache driver interface
	 * @param \phpbb\db\driver\driver_interface		$db			Database driver
	 * @param \phpbb\template\template				$template	Template object
	 * @param \phpbb\user							$user		User object
	 * @access public
	 */
	public function __construct(\phpbb\cache\driver\driver_interface $cache, \phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
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
			// TODO: another clear_cache call is required when topic is
			// soft-deleted: \phpbb\content_visibility.php@set_topic_visibility
			// new core event needed first
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

			$sql = 'SELECT COUNT(topic_poster) as user_topics
					FROM ' . TOPICS_TABLE . '
					WHERE topic_poster = ' . $this->user->data['user_id'] . '
						AND topic_visibility = ' . ITEM_APPROVED;
			$result = $this->db->sql_query($sql, 3600);
			$user_topics = $this->db->sql_fetchfield('user_topics');
			$this->db->sql_freeresult($result);

			$sql = 'SELECT r.rank_title, u.user_rank
					FROM ' . RANKS_TABLE . ' as r, ' . USERS_TABLE . ' as u
					WHERE u.user_id = ' . $this->user->data['user_id'] . '
						AND rank_id = u.user_rank';
			$result = $this->db->sql_query($sql);
			$user_rank = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$this->template->assign_vars(array(
				'US_IP'			=> $this->user->data['user_ip'],
				'US_REGDATE'	=> $this->user->format_date($this->user->data['user_regdate']),
				'US_ID'			=> $this->user->data['user_id'],
				'US_POSTS'		=> $this->user->data['user_posts'],
				'US_RTITLE'		=> ($user_rank['rank_title'] != '') ? $user_rank['rank_title'] : $this->user->lang('US_NO_RANK'),
				'US_TOPICS'		=> $user_topics,
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
