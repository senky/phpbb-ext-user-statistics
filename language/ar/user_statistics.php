<?php
/**
*
* User Statistics extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'US_USER_IP'		=> 'رقمك الـ IP',
	'US_USER_REGDATE'	=> 'تاريخ إنضمامك',
	'US_USER_ID'		=> 'رقم عضويتك',
	'US_USER_POSTS'		=> 'عدد مُشاركاتك',
	'US_USER_TOPICS'	=> 'عدد مواضيعك',
	'US_USER_RTITLE'	=> 'عنوان رُتبتك',
	'US_NO_RANK'		=> 'لا توجد',
));
