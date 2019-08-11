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
	'US_USER_IP'		=> 'Jouw IP',
	'US_USER_REGDATE'	=> 'Jouw registratie datum',
	'US_USER_ID'		=> 'Jouw ID',
	'US_USER_POSTS'		=> 'Jouw berichten',
	'US_USER_TOPICS'	=> 'Jouw onderwerpen',
	'US_USER_RTITLE'	=> 'Jouw rang titel',
	'US_NO_RANK'		=> 'Geen rang',
));
