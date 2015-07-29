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

class event_listener_test extends \phpbb_database_test_case
{
	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	*/
	static protected function setup_extensions()
	{
		return array('senky/userstatistics');
	}

	/** @var \senky\userstatistics\event\listener */
	protected $listener;

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

		global $phpbb_dispatcher, $phpbb_extension_manager, $phpbb_root_path;

		// Mock some global classes that may be called during code execution
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$phpbb_extension_manager = new \phpbb_mock_extension_manager($phpbb_root_path);

		// Load/Mock classes required by the event listener class
		$this->db = $this->new_dbal();

		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();

		$this->user = new \phpbb\user('\phpbb\datetime');
		$this->user->timezone = new \DateTimeZone('UTC');
		$this->user->lang['datetime'] = array();
		$this->user->data['user_ip'] = '127.0.0.1';
		$this->user->data['user_regdate'] = 946684800;
		$this->user->data['user_id'] = '1';
		$this->user->data['user_posts'] = '20';
	}

	/**
	* Create our event listener
	*/
	protected function set_listener()
	{
		$this->listener = new \senky\userstatistics\event\listener(
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
		), array_keys(\senky\userstatistics\event\listener::getSubscribedEvents()));
	}

	/**
	* Test the set_template_variables event
	*/
	public function test_set_template_variables()
	{
		$this->set_listener();

		$this->template->expects($this->once())
			->method('assign_vars')
			->with(array(
				'US_IP'			=> '127.0.0.1',
				'US_REGDATE'	=> '01.01.2000 0:00:00',
				'US_ID' 		=> '1',
				'US_POSTS'		=> '20',
				'US_RTITLE'		=> 'Site Admin',
				'US_TOPICS'		=> '3',
			));
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.index_modify_page_title', array($this->listener, 'set_template_variables'));
		$dispatcher->dispatch('core.index_modify_page_title');
	}
}
