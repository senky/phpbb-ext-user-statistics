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
	'US_USER_IP'		=> 'Tvoja IP',
	'US_USER_REGDATE'	=> 'Tvoj dátum registrácie',
	'US_USER_ID'		=> 'Tvoje ID',
	'US_USER_POSTS'		=> 'Tvoje príspevky',
	'US_USER_TOPICS'	=> 'Tvoje témy',
	'US_USER_RTITLE'	=> 'Tvoja hodnosť',
	'US_NO_RANK'		=> 'Žiadna hodnosť',
));
