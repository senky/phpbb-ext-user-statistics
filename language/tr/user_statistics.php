<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
* Turkish translation by ESQARE (http://www.phpbbturkey.com)
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
	'US_USER_IP'		=> 'IP adresiniz',
	'US_USER_REGDATE'	=> 'Kayıt tarihiniz',
	'US_USER_ID'		=> 'ID numaranız',
	'US_USER_POSTS'		=> 'Mesajlarınız',
	'US_USER_TOPICS'	=> 'Başlıklarınız',
	'US_USER_RTITLE'	=> 'Rütbeniz',
	'US_NO_RANK'		=> 'Rütbe yok',
));
