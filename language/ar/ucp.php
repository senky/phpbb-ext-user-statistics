<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2019 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Translated By : Bassel Taha Alhitary <http://www.alhitary.net>
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
	'USER_STATISTICS_LOCATION'	=> 'موقع الإحصائيات',
	'TOP'						=> 'أعلى',
	'BOTTOM'					=> 'أسفل',
));
