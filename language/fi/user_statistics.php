<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'US_USER_IP'		=> 'IP-osoitteesi',
	'US_USER_REGDATE'	=> 'Rekisteröidyit',
	'US_USER_ID'		=> 'Järjestysnumerosi',
	'US_USER_POSTS'		=> 'Viestisi',
	'US_USER_TOPICS'	=> 'Aiheesi',
	'US_USER_RTITLE'	=> 'Arvonimesi',
	'US_NO_RANK'		=> 'Ei ole',
));
