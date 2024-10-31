<?php
/*
Plugin Name: Send link to friend
Description: If user think the content is useful to their friend, they can use this form to send the URL instead of copy and paste the URL into email.
Author: Gopi Ramasamy
Version: 12.4
Plugin URI: http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/
Author URI: http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/
Donate link: http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: send-link-to-friend
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function gSendtofriend_add_to_menu() {
	if (is_admin()) {
		add_options_page( __('Send link to friend', 'send-link-to-friend'), 
				__('Send link to friend', 'send-link-to-friend'), 'manage_options', 'send-link-to-friend', 'gSendtofriend_admin_options' );
	}
}

function gSendtofriend_admin_options() {
	global $wpdb;
	?>
	<div class="wrap">
        <div id="icon-themes" class="icon32"></div>
        <h2><?php _e('Send link to friend', 'send-link-to-friend'); ?></h2>
		<?php settings_errors(); ?>
		<?php
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options';
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=send-link-to-friend&tab=mail_setting" class="nav-tab <?php echo $active_tab == 'mail_setting' ? 'nav-tab-active' : ''; ?>">Mail Setting</a>
			<a href="?page=send-link-to-friend&tab=display_setting" class="nav-tab <?php echo $active_tab == 'display_setting' ? 'nav-tab-active' : ''; ?>">Display Setting</a>
			<a href="?page=send-link-to-friend&tab=recaptcha_setting" class="nav-tab <?php echo $active_tab == 'recaptcha_setting' ? 'nav-tab-active' : ''; ?>">reCaptcha Setting</a>
			<a href="?page=send-link-to-friend&tab=message_setting" class="nav-tab <?php echo $active_tab == 'message_setting' ? 'nav-tab-active' : ''; ?>">Messages</a>
			<a href="http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/" target="_blank" class="nav-tab">Faq & Help</a>
        </h2>
		<?php
		if (isset($_POST['gSendtofriend_form_submit']) && $_POST['gSendtofriend_form_submit'] == 'yes') {
		?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'send-link-to-friend'); ?></strong></p>
			</div>
		<?php
		}
		?>
		<form name="cas_form" method="post" action="">
		<table class="form-table" role="presentation">
	  		<tbody>
				<?php
				if( $active_tab == 'mail_setting' ) {
					gSendtofriend_mail_setting();
				} elseif( $active_tab == 'display_setting' ) {
					gSendtofriend_display_setting();
				} elseif( $active_tab == 'recaptcha_setting' ) {
					gSendtofriend_recaptcha_setting();
				} elseif( $active_tab == 'message_setting' ) {
					gSendtofriend_message_setting();
				}
				else {
					gSendtofriend_mail_setting();
				}
				?>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="gSendtofriend_form_submit" value="yes"/>
			<input name="gSendtofriend_submit" id="gSendtofriend_submit" class="button button-primary" value="<?php _e('Submit', 'send-link-to-friend'); ?>" type="submit" />&nbsp;
			<a class="button button-primary" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/"><?php _e('Help', 'send-link-to-friend'); ?></a>
		</p>
		<?php wp_nonce_field('gSendtofriend_form_setting'); ?>
		</form>
		<div class="clear"></div>
		<p class="description">
			<?php _e('Check official website for more information', 'send-link-to-friend'); ?> 
  			<a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/send-link-to-friend/"><?php _e('click here', 'send-link-to-friend'); ?></a>
		</p>
    </div>
	<?php
}

function gSendtofriend_mail_setting() {
	
	$gSendtofriend_fromname = get_option('gSendtofriend_fromname');
	$gSendtofriend_fromemail = get_option('gSendtofriend_fromemail');
	$gSendtofriend_mailcontent = get_option('gSendtofriend_mailcontent');
	$gSendtofriend_subject = get_option('gSendtofriend_subject');
	
	if (isset($_POST['gSendtofriend_form_submit']) && $_POST['gSendtofriend_form_submit'] == 'yes') {
		check_admin_referer('gSendtofriend_form_setting');
		
		$gSendtofriend_fromname 	= stripslashes(sanitize_text_field($_POST['gSendtofriend_fromname']));
		$gSendtofriend_fromemail 	= stripslashes(sanitize_text_field($_POST['gSendtofriend_fromemail']));
		$gSendtofriend_mailcontent 	= stripslashes(wp_filter_post_kses($_POST['gSendtofriend_mailcontent']));
		$gSendtofriend_subject 		= stripslashes(wp_filter_post_kses($_POST['gSendtofriend_subject']));
		
		update_option('gSendtofriend_fromname', $gSendtofriend_fromname );
		update_option('gSendtofriend_fromemail', $gSendtofriend_fromemail );
		update_option('gSendtofriend_mailcontent', $gSendtofriend_mailcontent );
		update_option('gSendtofriend_subject', $gSendtofriend_subject );
	}
	?>
	<tr>
	  <th scope="row"> <label>
		<?php _e('Sender of email', 'send-link-to-friend'); ?>
		</label>
	  </th>
	  <td><input name="gSendtofriend_fromname" type="text" id="gSendtofriend_fromname" value="<?php echo $gSendtofriend_fromname; ?>" maxlength="225" />
		<input name="gSendtofriend_fromemail" type="text" id="gSendtofriend_fromemail" value="<?php echo $gSendtofriend_fromemail; ?>" size="35" maxlength="225" />
		<p class="description">
		  <?php _e('Choose a FROM name and FROM email address for all emails from this plugin.', 'send-link-to-friend'); ?>
		</p></td>
	</tr>
	<tr>
	  <th scope="row"> <label>
		<?php _e('Mail subject', 'send-link-to-friend'); ?>
		</label>
	  </th>
	  <td><input name="gSendtofriend_subject" type="text" id="gSendtofriend_subject" value="<?php echo $gSendtofriend_subject; ?>" size="62" maxlength="225" />
		<p class="description">
		  <?php _e('Please enter the subject for send link mail.', 'send-link-to-friend'); ?>
		</p></td>
	</tr>
	<tr>
	  <th scope="row"> <label>
		<?php _e('Mail content', 'send-link-to-friend'); ?>
		</label>
	  </th>
	  <td><textarea size="100" rows="9" cols="60" id="gSendtofriend_mailcontent"  name="gSendtofriend_mailcontent"><?php echo $gSendtofriend_mailcontent; ?></textarea>
		<p class="description">
		  <?php _e('Please enter the content for send link mail.', 'send-link-to-friend'); ?>
		  (Keywords : ###SITENAME###, ###SENDLINK###, ###MESSAGE###) </p></td>
	</tr>
	<?php
}

function gSendtofriend_display_setting() {

	$gSendtofriend_On_Homepage = get_option('gSendtofriend_On_Homepage');
	$gSendtofriend_On_Posts = get_option('gSendtofriend_On_Posts');
	$gSendtofriend_On_Pages = get_option('gSendtofriend_On_Pages');
	
	if (isset($_POST['gSendtofriend_form_submit']) && $_POST['gSendtofriend_form_submit'] == 'yes') {
		check_admin_referer('gSendtofriend_form_setting');
		
		$gSendtofriend_On_Homepage 	= stripslashes(sanitize_text_field($_POST['gSendtofriend_On_Homepage']));
		$gSendtofriend_On_Posts 	= stripslashes(sanitize_text_field($_POST['gSendtofriend_On_Posts']));
		$gSendtofriend_On_Pages 	= stripslashes(sanitize_text_field($_POST['gSendtofriend_On_Pages']));
		
		if($gSendtofriend_On_Homepage != "YES" && $gSendtofriend_On_Homepage != "NO") { $gSendtofriend_On_Homepage = "YES"; }
		if($gSendtofriend_On_Posts != "YES" && $gSendtofriend_On_Posts != "NO") { $gSendtofriend_On_Posts = "YES"; }
		if($gSendtofriend_On_Pages != "YES" && $gSendtofriend_On_Pages != "NO") { $gSendtofriend_On_Pages = "YES"; }
		
		update_option('gSendtofriend_On_Homepage', $gSendtofriend_On_Homepage );
		update_option('gSendtofriend_On_Posts', $gSendtofriend_On_Posts );
		update_option('gSendtofriend_On_Pages', $gSendtofriend_On_Pages );
	}
	?>
	<tr>
		<th scope="row">
			<label><?php _e('Display on home page', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<select name="gSendtofriend_On_Homepage" id="gSendtofriend_On_Homepage">
				<option value='YES' <?php if($gSendtofriend_On_Homepage == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
				<option value='NO' <?php if($gSendtofriend_On_Homepage == 'NO') { echo "selected='selected'" ; } ?>>No</option>
			</select>
			<p class="description"><?php _e('Do you want to show this form in home page? this option is only for PHP code.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label><?php _e('Display on WP posts', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<select name="gSendtofriend_On_Posts" id="gSendtofriend_On_Posts">
				<option value='YES'  <?php if($gSendtofriend_On_Posts == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
				<option value='NO'  <?php if($gSendtofriend_On_Posts == 'NO') { echo "selected='selected'" ; } ?>>No</option>
			</select>
			<p class="description"><?php _e('Do you want to show this form on wp post? this option is only for PHP code.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label><?php _e('Display on WP pages', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<select name="gSendtofriend_On_Pages" id="gSendtofriend_On_Pages">
				<option value='YES' <?php if($gSendtofriend_On_Pages == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
				<option value='NO' <?php if($gSendtofriend_On_Pages == 'NO') { echo "selected='selected'" ; } ?>>No</option>
			</select>
			<p class="description"><?php _e('Do you want to show this form on wp page? this option is only for PHP code.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<?php
}

function gSendtofriend_recaptcha_setting() {

	$gSendtofriend_captcha = get_option('gSendtofriend_captcha', '');
	if($gSendtofriend_captcha == "") {
		add_option('gSendtofriend_captcha', "NO");
	}
	
	$gSendtofriend_captcha_secret = get_option('gSendtofriend_captcha_secret', '');
	if($gSendtofriend_captcha_secret == "") {
		add_option('gSendtofriend_captcha_secret', "");
	}
	
	$gSendtofriend_captcha_sitekey = get_option('gSendtofriend_captcha_sitekey', '');
	if($gSendtofriend_captcha_sitekey == "") {
		add_option('gSendtofriend_captcha_sitekey', "");
	}
	
	if (isset($_POST['gSendtofriend_form_submit']) && $_POST['gSendtofriend_form_submit'] == 'yes') {
		check_admin_referer('gSendtofriend_form_setting');
		
		$gSendtofriend_captcha 	= sanitize_text_field($_POST['gSendtofriend_captcha']);
		$gSendtofriend_captcha_secret 	= sanitize_text_field($_POST['gSendtofriend_captcha_secret']);
		$gSendtofriend_captcha_sitekey 	= sanitize_text_field($_POST['gSendtofriend_captcha_sitekey']);
			
		update_option('gSendtofriend_captcha', $gSendtofriend_captcha );
		update_option('gSendtofriend_captcha_secret', $gSendtofriend_captcha_secret );
		update_option('gSendtofriend_captcha_sitekey', $gSendtofriend_captcha_sitekey );
	}

	$gSendtofriend_message = get_option('gSendtofriend_captcha');
	$gSendtofriend_message = get_option('gSendtofriend_captcha_secret');
	$gSendtofriend_message = get_option('gSendtofriend_captcha_sitekey');
	?>
	<tr>
		<th scope="row">
			<label><?php _e('reCaptcha option', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<select name="gSendtofriend_captcha" id="gSendtofriend_captcha">
				<option value='NO' <?php if($gSendtofriend_captcha == 'NO') { echo 'selected="selected"' ; } ?>>NO (Do not add captcha)</option>
				<option value='YES' <?php if($gSendtofriend_captcha == 'YES') { echo 'selected="selected"' ; } ?>>YES (Add captcha)</option>
			  </select>
			<p class="description"><?php _e('Add reCaptcha in the send link form.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label><?php _e('reCaptcha Secret key', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<input name="gSendtofriend_captcha_secret" type="text" id="gSendtofriend_captcha_secret" value="<?php echo $gSendtofriend_captcha_secret; ?>" maxlength="225" size="75"  />
			<p class="description"><?php _e('Please enter your secret key for reCaptcha.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label><?php _e('reCaptcha Site key', 'send-link-to-friend'); ?></label>
		</th>
		<td>
			<input name="gSendtofriend_captcha_sitekey" type="text" id="gSendtofriend_captcha_sitekey" value="<?php echo $gSendtofriend_captcha_sitekey; ?>" maxlength="225" size="75"  />
			<p class="description"><?php _e('Please enter your site key for reCaptcha.', 'send-link-to-friend'); ?></p>
		</td>
	</tr>
	<?php
}

function gSendtofriend_message_setting() {

	$gSendtofriend_message = get_option('gSendtofriend_message', '');
	if($gSendtofriend_message == "") {
		add_option('gSendtofriend_message', "Message sent successfully to your friend email.");
	}
	
	if (isset($_POST['gSendtofriend_form_submit']) && $_POST['gSendtofriend_form_submit'] == 'yes') {
		check_admin_referer('gSendtofriend_form_setting');
		
		$gSendtofriend_message 	= stripslashes(wp_filter_post_kses($_POST['gSendtofriend_message']));
		update_option('gSendtofriend_message', $gSendtofriend_message );
	}

	$gSendtofriend_message = get_option('gSendtofriend_message');
	?>
	<tr>
	  <th scope="row"> <label>
		<?php _e('Successful message', 'send-link-to-friend'); ?>
		</label>
	  </th>
	  <td>
	  	<textarea size="100" rows="4" cols="60" id="gSendtofriend_message"  name="gSendtofriend_message"><?php echo $gSendtofriend_message; ?></textarea>
		<p class="description">
		  <?php _e('Message to display after form submission successfully.', 'send-link-to-friend'); ?>
		</p>
	  </td>
	</tr>
	<?php
}

function gSendtofriend_widget_loading() {
	register_widget( 'gSendtofriend_widget_register' );
}

class gSendtofriend_widget_register extends WP_Widget 
{
	function __construct() {
		$widget_ops = array('classname' => 'widget_text gSendtofriend-widget', 'description' => __('Send link to friend', 'send-link-to-friend'), 'send-link-to-friend');
		parent::__construct('send-link-to-friend', __('Send link to friend', 'send-link-to-friend'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		
		$sltf_title 	= apply_filters( 'widget_title', empty( $instance['sltf_title'] ) ? '' : $instance['sltf_title'], $instance, $this->id_base );
		$sltf_desc		= isset($instance['sltf_desc']) ? $instance['sltf_desc'] : '';
		$sltf_home_dis	= isset($instance['sltf_home_dis']) ? $instance['sltf_home_dis'] : '';
		$sltf_post_dis	= isset($instance['sltf_post_dis']) ? $instance['sltf_post_dis'] : '';
		$sltf_page_dis	= isset($instance['sltf_page_dis']) ? $instance['sltf_page_dis'] : '';

		$display = "";
		if(is_home() && $sltf_home_dis == 'YES') {	
			$display = "show";
		}
		if(is_single() && $sltf_post_dis == 'YES') {	
			$display = "show";
		}
		if(is_page() && $sltf_page_dis == 'YES') {	
			$display = "show";
		}
		if(is_archive() && get_option('gSendtofriend_On_Archives') == 'YES') {	
			$display = "show";	
		}
		if(is_search() && get_option('gSendtofriend_On_Search') == 'YES') {	
			$display = "show";	
		}
		
		if($display == '') {
			return '';
		}
	
		echo $args['before_widget'];
		if ( ! empty( $sltf_title ) )
		{
			echo $args['before_title'] . $sltf_title . $args['after_title'];
		}
					
		$data = array(
			'sltf_title' 		=> $sltf_title,
			'sltf_desc' 		=> $sltf_desc,
			'sltf_home_dis' 	=> $sltf_home_dis,
			'sltf_post_dis' 	=> $sltf_post_dis,
			'sltf_page_dis' 	=> $sltf_page_dis
		);
		
		gSendtofriend_form( $data );
		
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) {		
		$instance 					= $old_instance;
		$instance['sltf_title'] 	= ( ! empty( $new_instance['sltf_title'] ) ) ? strip_tags( $new_instance['sltf_title'] ) : '';
		$instance['sltf_desc'] 		= ( ! empty( $new_instance['sltf_desc'] ) ) ? strip_tags( $new_instance['sltf_desc'] ) : '';
		$instance['sltf_home_dis'] 	= ( ! empty( $new_instance['sltf_home_dis'] ) ) ? strip_tags( $new_instance['sltf_home_dis'] ) : '';
		$instance['sltf_post_dis'] 	= ( ! empty( $new_instance['sltf_post_dis'] ) ) ? strip_tags( $new_instance['sltf_post_dis'] ) : '';
		$instance['sltf_page_dis'] 	= ( ! empty( $new_instance['sltf_page_dis'] ) ) ? strip_tags( $new_instance['sltf_page_dis'] ) : '';
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array(
			'sltf_title' 		=> '',
		    'sltf_desc' 		=> '',
			'sltf_home_dis' 	=> '',
			'sltf_post_dis' 	=> '',
			'sltf_page_dis' 	=> ''
        );
		
		$instance 		= wp_parse_args( (array) $instance, $defaults);
		$sltf_title 	= isset($instance['sltf_title']) ? $instance['sltf_title'] : '';
        $sltf_desc 		= isset($instance['sltf_desc']) ? $instance['sltf_desc'] : '';
		$sltf_home_dis 	= isset($instance['sltf_home_dis']) ? $instance['sltf_home_dis'] : '';
		$sltf_post_dis 	= isset($instance['sltf_post_dis']) ? $instance['sltf_post_dis'] : '';
		$sltf_page_dis 	= isset($instance['sltf_page_dis']) ? $instance['sltf_page_dis'] : '';
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('sltf_title'); ?>"><?php _e('Widget title', 'send-link-to-friend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('sltf_title'); ?>" name="<?php echo $this->get_field_name('sltf_title'); ?>" type="text" value="<?php echo $sltf_title; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('sltf_desc'); ?>"><?php _e('Short description for your send link form.', 'send-link-to-friend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('sltf_desc'); ?>" name="<?php echo $this->get_field_name('sltf_desc'); ?>" type="text" value="<?php echo $sltf_desc; ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('sltf_home_dis'); ?>"><?php _e('Display on home page', 'send-link-to-friend'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('sltf_home_dis'); ?>" name="<?php echo $this->get_field_name('sltf_home_dis'); ?>">
				<option value="YES" <?php $this->sltf_selected($sltf_home_dis == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->sltf_selected($sltf_home_dis == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('sltf_post_dis'); ?>"><?php _e('Display on WP posts', 'send-link-to-friend'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('sltf_post_dis'); ?>" name="<?php echo $this->get_field_name('sltf_post_dis'); ?>">
				<option value="YES" <?php $this->sltf_selected($sltf_post_dis == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->sltf_selected($sltf_post_dis == 'NO'); ?>>NO</option>
			</select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('sltf_page_dis'); ?>"><?php _e('Display on WP pages', 'send-link-to-friend'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('sltf_page_dis'); ?>" name="<?php echo $this->get_field_name('sltf_page_dis'); ?>">
				<option value="YES" <?php $this->sltf_selected($sltf_page_dis == 'YES'); ?>>YES</option>
				<option value="NO" <?php $this->sltf_selected($sltf_page_dis == 'NO'); ?>>NO</option>
			</select>
        </p>
			
		<?php
	}
	
	function sltf_selected($var) {
		if ($var==1 || $var==true) {
			echo 'selected="selected"';
		}
	}
}

function gSendtofriend_activation() 
{
	$admin_email = get_option('admin_email');
	$blogname = get_option('blogname');
	if($admin_email == "") {
		$admin_email = "admin@sendtofriend.com";
	}
	$contant = "Hi Friend, \r\n\r\nA friend has sent you a link to ###SITENAME###\r\n\r\n###SENDLINK###\r\n\r\n###MESSAGE###\r\n\r\nThank You";
			
	add_option('gSendtofriend_fromname', "Admin");
	add_option('gSendtofriend_fromemail', $admin_email);
	add_option('gSendtofriend_On_Homepage', "YES");
	add_option('gSendtofriend_On_Posts', "YES");
	add_option('gSendtofriend_On_Pages', "YES");
	add_option('gSendtofriend_On_Archives', "NO");
	add_option('gSendtofriend_On_Search', "NO");
	add_option('gSendtofriend_mailcontent', $contant);
	add_option('gSendtofriend_subject', "Recommended Link");
	add_option('gSendtofriend_captcha', "NO");
	add_option('gSendtofriend_captcha_secret', "");
	add_option('gSendtofriend_captcha_sitekey', "");
	add_option('gSendtofriend_message', "Your message and page link sent successfully to your friend.");
}

function gSendtofriend_deactivation() {
	// No action required.
}

function gSendtofriend_shortcode( $atts ) {
	ob_start();
	
	//[send-link-to-friend]

	$atts = shortcode_atts( array(
		'sltf_desc' 	=> '',
		'sltf_home_dis' => '',
		'sltf_post_dis' => '',
		'sltf_page_dis' => ''
	), $atts, 'send-link-to-friend' );

	$sltf_desc 		= isset($atts['sltf_desc']) ? $atts['sltf_desc'] : '';
	$sltf_home_dis 	= isset($atts['sltf_home_dis']) ? $atts['sltf_home_dis'] : '';
	$sltf_post_dis 	= isset($atts['sltf_post_dis']) ? $atts['sltf_post_dis'] : '';
	$sltf_page_dis 	= isset($atts['sltf_page_dis']) ? $atts['sltf_page_dis'] : '';

	$data = array(
		'sltf_desc' 	=> $sltf_desc,
		'sltf_home_dis' => $sltf_home_dis,
		'sltf_post_dis' => $sltf_post_dis,
		'sltf_page_dis' => $sltf_page_dis
	);

	gSendtofriend_form( $data );

	return ob_get_clean();
}

function gSendtofriend() {
	$sltf_home_dis = get_option('gSendtofriend_On_Homepage');
	$sltf_post_dis = get_option('gSendtofriend_On_Posts');
	$sltf_page_dis = get_option('gSendtofriend_On_Pages');
	
	$data = array(
		'sltf_desc' 	=> '',
		'sltf_home_dis' => $sltf_home_dis,
		'sltf_post_dis' => $sltf_post_dis,
		'sltf_page_dis' => $sltf_page_dis
	);

	gSendtofriend_form( $data );
}

function gSendtofriend_form( $data = array() ) {	
	if(count($data) == 0) {
		return "";
	}

	$sltf_desc 		= $data['sltf_desc'];
	$sltf_home_dis	= $data['sltf_home_dis'];
	$sltf_post_dis	= $data['sltf_post_dis'];
	$sltf_page_dis	= $data['sltf_page_dis'];
	
	$sltf_desc_html = "";
	if($sltf_desc	<> "") {
		$sltf_desc_html = '<p>';
		$sltf_desc_html .= $sltf_desc;
		$sltf_desc_html .= '</p>';
	}
		
	$loading_image_path = plugins_url(). '/send-link-to-friend/ajax-loader.gif';
	$nonce = wp_create_nonce( 'sltf-nonce' );
	$unique_no = time();
	$sendlinks = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
	
	//////////////////////////////Robot verification//////////////////////////////////////////////////
	$sltf_recaptcha_js = '';
	$sltf_recaptcha_html = '';
	$sltf_captcha = get_option('gSendtofriend_captcha', '');
	if($sltf_captcha == 'YES') {
		$sltf_recaptcha_js = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		$sltf_sitekey = get_option('gSendtofriend_captcha_sitekey');
		$sltf_recaptcha_html = '<p>';
		$sltf_recaptcha_html .= '<div class="g-recaptcha" data-sitekey="'.$sltf_sitekey.'"></div>';
		$sltf_recaptcha_html .= '</p>';
	}
	//////////////////////////////Robot verification//////////////////////////////////////////////////
	?>
	<?php echo $sltf_recaptcha_js; ?>
	<div class="send_link_to_friend">
		<?php echo $sltf_desc_html; ?>
		<form action="#" method="post" class="sltf_form" id="sltf_form_<?php echo $unique_no; ?>">
			<p>
				<?php _e('Friend Email', 'send-link-to-friend'); ?><br />
				<input type="text" name="email" id="email" placeholder="Email" value="" maxlength="225" required/>
			</p>
			<p>
				<?php _e('Enter your message', 'send-link-to-friend'); ?><br />
				<textarea name="friendmessage" rows="3" id="friendmessage" placeholder="Message"></textarea>
			</p>
			<?php echo $sltf_recaptcha_html; ?>
			<input name="submit" id="sltf_form_submit_<?php echo $unique_no; ?>" value="Submit" type="submit" />
			<span class="sltf_form_spinner" id="sltf-loading-image" style="display:none;">
				<img src="<?php echo $loading_image_path; ?>" />
			</span>
			<input type="hidden" name="sendlink" id="sendlink" value="<?php echo $sendlinks; ?>"  />
			<input name="form_nonce" id="form_nonce" value="<?php echo $nonce; ?>" type="hidden"/>
		</form>	
		<span class="sltf_form_message" id="sltf_form_message_<?php echo $unique_no; ?>"></span>
	</div><br />
	<?php
}

function gSendtofriend_load_scripts_front() {
	wp_enqueue_script( 'send-link-to-friend', plugins_url() . '/send-link-to-friend/send-link-to-friend.js', array( 'jquery' ), '2.2', false );
	
	$sltf_data = array(
		'messages' => array(
			'sltf_required_field'    => __( 'Please enter email address.', 'send-link-to-friend' ),
			'sltf_invalid_email'     => __( 'Email address seems invalid.', 'send-link-to-friend' ),
			'sltf_unexpected_error'  => __( 'Oops.. Unexpected error occurred.', 'send-link-to-friend' ),
			'sltf_sent_successfull'  => __( 'Message sent successfully to your friend email.', 'send-link-to-friend' ),
			'sltf_invalid_captcha'   => __( 'Robot verification failed, please try again.', 'send-link-to-friend' ),
			'sltf_invalid_key'   	 => __( 'Robot verification failed, invalid key.', 'send-link-to-friend' )
		),
		'sltf_ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	
	wp_localize_script( 'send-link-to-friend', 'sltf_data', $sltf_data );
}

function gSendtofriend_load_style_front() {
	echo '<style>';
	echo '.sltf_form_message.success { color: #008000; }';
	echo '.sltf_form_message.error { color: #ff0000; }';
	echo '</style>';
}

function gSendtofriend_process_send() {
	$response = array( 'status' => 'SUCCESS', 'message' => '' );
	
	//////////////////////////////Robot verification//////////////////////////////////////////////////
	$gSendtofriend_captcha = get_option('gSendtofriend_captcha', '');
	if($gSendtofriend_captcha == 'YES') {
		$secret = get_option('gSendtofriend_captcha_secret');
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['sltf_g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if(!$responseData->success) {
			$response['message'] = 'sltf_invalid_captcha';
			$response['status'] = 'ERROR';
			gSendtofriend_do_response( $response );
			exit;
		}
	}
	//////////////////////////////Robot verification//////////////////////////////////////////////////
	
	$sltf_submit    	= gSendtofriend_get_posted_data( 'sltf_submit' ,'' , true);
	$sltf_form_nonce 	= gSendtofriend_get_posted_data( 'sltf_form_nonce' ,'' , true );
	
	if ( $sltf_submit === 'submitted' && ! empty( $sltf_form_nonce ) ) {
		
		$data = gSendtofriend_get_posted_data();
		$sltf_email = isset( $data['sltf_email'] ) ? $data['sltf_email'] : '';
		
		if ( $sltf_email == '') {
			$response['message'] = 'sltf_required_field';
			$response['status'] = 'ERROR';
		}
		
		if ( ! filter_var( $sltf_email, FILTER_VALIDATE_EMAIL ) ) {
			$response['message'] = 'sltf_invalid_email';
			$response['status'] = 'ERROR';
		}
		
		if ($response['status'] == 'SUCCESS') {
			$sltf_friendmessage = isset( $data['sltf_friendmessage'] ) ? $data['sltf_friendmessage'] : '';
			$sltf_sendlink = isset( $data['sltf_sendlink'] ) ? $data['sltf_sendlink'] : '';
			
			$sender_name = get_option('gSendtofriend_fromname');
			$sender_email = get_option('gSendtofriend_fromemail');
			$subject = get_option('gSendtofriend_subject');
			$content = get_option('gSendtofriend_mailcontent');
			$site_name = get_option('blogname');
			
			if($subject == "") {
				$subject = "Recommended Link";
			}
							
			$headers  = "From: \"$sender_name\" <$sender_email>\n";
			$headers .= "Return-Path: <" . $sender_email . ">\n";
			$headers .= "Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
			$headers .= "X-Mailer: PHP" . phpversion() . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
			$headers .= "Content-type: text/html\r\n"; 
			
			$content = stripslashes($content);		
			$sltf_friendmessage = stripslashes($sltf_friendmessage);
			$content = str_replace("###SITENAME###", $site_name, $content);
			$content = str_replace("###SENDLINK###", $sltf_sendlink, $content);
			$content = str_replace("###MESSAGE###", $sltf_friendmessage, $content);
			$content = str_replace("\r\n", "<br />", $content);
			$content = nl2br($content);
					
			wp_mail($sltf_email, $subject, $content, $headers);
			
			$response['message'] = 'sltf_sent_successfull';
		}	
	}
	
	gSendtofriend_do_response( $response );
	exit;
}

function gSendtofriend_get_posted_data( $var = '', $default = '', $clean = true ) {
	return gSendtofriend_posted_data( $_POST, $var, $default, $clean );
}

function gSendtofriend_posted_data( $array = array(), $var = '', $default = '', $clean = false ) {
	if ( ! empty( $var ) ) {
		$value = isset( $array[ $var ] ) ? wp_unslash( $array[ $var ] ) : $default;
	} else {
		$value = wp_unslash( $array );
	}

	if ( $clean ) {
		$value = gSendtofriend_posted_clean_data( $value );
	}
	return $value;
}

function gSendtofriend_posted_clean_data( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'gSendtofriend_posted_clean_data', $var );
	} 
	else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

function gSendtofriend_do_response( $response ) {
	$message = isset( $response['message'] ) ? $response['message'] : '';
	$response['message_text'] = '';
	if ( ! empty( $message ) ) {
		if($message == 'sltf_sent_successfull') {
			$gSendtofriend_message = get_option('gSendtofriend_message', '');
			if($gSendtofriend_message == "") {
				$response['message_text'] = 'Message sent successfully to your friend email.';
			}
			else {
				$response['message_text'] = $gSendtofriend_message;
			}
		}
		else {
			$response['message_text'] = gSendtofriend_get_messages( $message );
		}
	}

	echo json_encode( $response );
	exit;
}

function gSendtofriend_get_messages($message) {
	$messages = array(
		'sltf_required_field'    => __( 'Please enter email address.', 'send-link-to-friend' ),
		'sltf_invalid_email'     => __( 'Email address seems invalid.', 'send-link-to-friend' ),
		'sltf_unexpected_error'  => __( 'Oops.. Unexpected error occurred.', 'send-link-to-friend' ),
		'sltf_sent_successfull'  => __( 'Message sent successfully to your friend email.', 'send-link-to-friend' ),
		'sltf_invalid_captcha'   => __( 'Robot verification failed, please try again.', 'send-link-to-friend' ),
		'sltf_invalid_key'   	 => __( 'Robot verification failed, invalid key.', 'send-link-to-friend' ),
	);

	$messages = apply_filters('sltf_form_messages', $messages);
	if ( ! empty( $messages ) ) {
		return isset($messages[ $message ]) ? $messages[ $message ] : '';
	}
	
	return $messages;
}

add_shortcode( 'send-link-to-friend', 'gSendtofriend_shortcode');

add_action('admin_menu', 'gSendtofriend_add_to_menu');
add_action( 'widgets_init', 'gSendtofriend_widget_loading');

register_activation_hook(__FILE__, 'gSendtofriend_activation');
register_deactivation_hook(__FILE__, 'gSendtofriend_deactivation');

add_action('wp_enqueue_scripts', 'gSendtofriend_load_scripts_front' );
add_filter('wp_head', 'gSendtofriend_load_style_front' );

add_action( 'wp_ajax_send_link_to_friend', 'gSendtofriend_process_send', 10 );
add_action( 'wp_ajax_nopriv_send_link_to_friend', 'gSendtofriend_process_send', 10 );
?>