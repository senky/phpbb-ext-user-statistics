<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
* Russian translation by HD321kbps
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
	'US_USER_IP'		=> 'Ваш IP',
	'US_USER_REGDATE'	=> 'Ваша дата регистрации',
	'US_USER_ID'		=> 'Ваш ID',
	'US_USER_POSTS'		=> 'Ваши сообщения',
	'US_USER_TOPICS'	=> 'Ваши темы',
	'US_USER_RTITLE'	=> 'Ваш заголовок звания',
	'US_NO_RANK'		=> 'Нет ранга',
));
