<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace senky\userstatistics\tests\event;

class listener_test extends \phpbb_database_test_case
{
	/**
	 * Define the extensions to be tested
	 *
	 * @return string[] vendor/name of extension(s) to test
	 */
	protected static function setup_extensions()
	{
		return array('senky/userstatistics');
	}

	/** @var \senky\userstatistics\event\listener */
	protected $listener;
	protected $sql_user_topics;

	protected $db;
	protected $template;
	protected $user;

	/**
	 * Get data set fixtures
	 *
	 * @return \PHPUnit_Extensions_Database_DataSet_XmlDataSet
	 */
	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/topics_ranks_users.xml');
	}

	/**
	 * Setup test environment
	 */
	public function setUp()
	{
		parent::setUp();

		global $phpbb_extension_manager, $cache, $phpbb_root_path;

		// Mock some global classes that may be called during code execution
		$phpbb_extension_manager = new \phpbb_mock_extension_manager($phpbb_root_path);
		$cache = new phpbb_cache_memory();

		// Load/Mock classes required by the event listener class
		$this->cache = $cache;
		$this->db = $this->new_dbal();
		$this->template = $this->getMockBuilder('\phpbb\template\template')->getMock();

		$this->user = new \phpbb\user('\phpbb\datetime');
		$this->user->timezone = new \DateTimeZone('UTC');
		$this->user->date_format = 'd.m.Y G:i:s';
		$this->user->lang['datetime'] = array();
		$this->user->data['is_registered'] = true;
		$this->user->data['user_ip'] = '127.0.0.1';
		$this->user->data['user_regdate'] = '946684800';
		$this->user->data['user_id'] = '1';
		$this->user->data['user_posts'] = '20';

		$this->sql_user_topics = 'SELECT COUNT(topic_poster) as user_topics
								FROM ' . TOPICS_TABLE . '
								WHERE topic_poster = 1
									AND topic_visibility = ' . ITEM_APPROVED;
	}

	/**
	 * Create our event listener
	 */
	protected function set_listener()
	{
		$this->listener = new \senky\userstatistics\event\listener(
			$this->cache,
			$this->db,
			$this->template,
			$this->user
		);
	}

	/**
	 * Test the event listener is constructed correctly
	 */
	public function test_construct()
	{
		$this->set_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.index_modify_page_title',
			'core.submit_post_end',
			'core.delete_topics_after_query',
		), array_keys(\senky\userstatistics\event\listener::getSubscribedEvents()));
	}

	/**
	 * Test the set_template_variables event for logged user
	 */
	public function test_set_template_variables_for_logged_user()
	{
		$this->set_listener();

		// ensure all required template variables are set
		$this->template->expects($this->once())
			->method('assign_vars')
			->with(array(
				'US_IP'			=> '127.0.0.1',
				'US_REGDATE'	=> '01.01.2000 0:00:00',
				'US_ID' 		=> '1',
				'US_POSTS'		=> '20',
				'US_RTITLE'		=> 'Site Admin',
				'US_TOPICS'		=> 3,
			));

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.index_modify_page_title', array($this->listener, 'set_template_variables'));
		$dispatcher->dispatch('core.index_modify_page_title');

		$query_id = $this->cache->sql_load($this->sql_user_topics);
		$this->assertInternalType('integer', $query_id);
	}

	/**
	 * Test the set_template_variables event for guest
	 */
	public function test_set_template_variables_for_guest()
	{
		$this->user->data['is_registered'] = false;

		$this->set_listener();

		// ensure that nothing inside the set_template_variables()
		// method is called for guest
		$this->template->expects($this->never())
			->method('assign_vars');

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.index_modify_page_title', array($this->listener, 'set_template_variables'));
		$dispatcher->dispatch('core.index_modify_page_title');
	}

	/**
	 * Test the clear_cache event for post method
	 */
	public function test_clear_cache_post()
	{
		$this->user->data['is_registered'] = true;

		$this->set_listener();

		// add listeners
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.submit_post_end', array($this->listener, 'clear_cache'));
		$dispatcher->addListener('core.delete_topics_after_query', array($this->listener, 'clear_cache'));

		// dispatch preposition
		$this->cache->put('sql_phpbb_topics', '1');

		$dispatcher->dispatch('core.submit_post_end');

		// ensure user topics cache is destroyed
		$query_id = $this->cache->sql_load($this->sql_user_topics);
		$this->assertFalse($query_id);

		// and again
		// dispatch preposition
		$this->cache->put('sql_phpbb_topics', '1');

		$dispatcher->dispatch('core.delete_topics_after_query');

		// ensure user topics cache is destroyed
		$query_id = $this->cache->sql_load($this->sql_user_topics);
		$this->assertFalse($query_id);
	}
}
