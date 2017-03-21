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
	'US_USER_IP'		=> 'Tvá IP',
	'US_USER_REGDATE'	=> 'Tvůj datum registrace',
	'US_USER_ID'		=> 'Tvé ID',
	'US_USER_POSTS'		=> 'Tvé příspěvky',
	'US_USER_TOPICS'	=> 'Tvé temy',
	'US_USER_RTITLE'	=> 'Tvá hodnost',
	'US_NO_RANK'		=> 'Žádná hodnost',
));
