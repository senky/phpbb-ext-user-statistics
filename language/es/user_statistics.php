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
	'US_USER_IP'		=> 'Su IP',
	'US_USER_REGDATE'	=> 'Su fecha de registro',
	'US_USER_ID'		=> 'Su ID',
	'US_USER_POSTS'		=> 'Sus mensajes',
	'US_USER_TOPICS'	=> 'Sus temas',
	'US_USER_RTITLE'	=> 'Su título de rango',
	'US_NO_RANK'		=> 'Sin rango',
));
