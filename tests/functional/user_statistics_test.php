<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace senky\userstatistics\tests\functional;

$_SERVER['PHPBB_FUNCTIONAL_URL'] = 'http://localhost/phpbb/phpBB/';

/**
* @group functional
*/
class user_statistics_test extends \phpbb_functional_test_case
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

	public function setUp()
	{
		global $phpbb_functional_url;

		parent::setUp();
		$this->add_lang_ext('senky/userstatistics', array('user_statistics'));
	}

	/**
	* Test loading the user statistics for logged user
	*/
	public function test_view_user_statistics()
	{
		$this->login();

		$crawler = self::request('GET', 'index.php');
		$this->assertContains('Your IP', $crawler->filter('#user-statistics thead th:first-child')->text());
	}

	/**
	* Test loading the user statistics for guests
	*/
	public function test_should_not_view_user_statistics()
	{
		$this->logout();

		$crawler = self::request('GET', 'index.php');
		$this->assertNull($crawler->filter('#user-statistics'));
	}
}
