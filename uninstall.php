<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('gSendtofriend_title');
delete_option('gSendtofriend_title_sm');
delete_option('gSendtofriend_fromname');
delete_option('gSendtofriend_fromemail');
delete_option('gSendtofriend_On_Homepage');
delete_option('gSendtofriend_On_Posts');
delete_option('gSendtofriend_On_Pages');
delete_option('gSendtofriend_On_Archives');
delete_option('gSendtofriend_On_Search');
delete_option('gSendtofriend_homeurl');
delete_option('gSendtofriend_mailcontent');
delete_option('gSendtofriend_subject');
delete_option('gSendtofriend_captcha');
delete_option('gSendtofriend_captcha_secret');
delete_option('gSendtofriend_captcha_sitekey');
delete_option('gSendtofriend_message');
 
// for site options in Multisite
delete_site_option('gSendtofriend_title');
delete_site_option('gSendtofriend_title_sm');
delete_site_option('gSendtofriend_fromname');
delete_site_option('gSendtofriend_fromemail');
delete_site_option('gSendtofriend_On_Homepage');
delete_site_option('gSendtofriend_On_Posts');
delete_site_option('gSendtofriend_On_Pages');
delete_site_option('gSendtofriend_On_Archives');
delete_site_option('gSendtofriend_On_Search');
delete_site_option('gSendtofriend_homeurl');
delete_site_option('gSendtofriend_mailcontent');
delete_site_option('gSendtofriend_subject');
delete_site_option('gSendtofriend_captcha');
delete_site_option('gSendtofriend_captcha_secret');
delete_site_option('gSendtofriend_captcha_sitekey');
delete_site_option('gSendtofriend_message');
