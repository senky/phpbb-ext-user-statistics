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
	'US_USER_IP'		=> 'Your IP',
	'US_USER_REGDATE'	=> 'Your registration date',
	'US_USER_ID'		=> 'Your ID',
	'US_USER_POSTS'		=> 'Your posts',
	'US_USER_TOPICS'	=> 'Your topics',
	'US_USER_RTITLE'	=> 'Your rank title',
	'US_NO_RANK'		=> 'No rank',
));
