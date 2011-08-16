<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Five Twenty CMS
 * @SubPackage PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");


class profile extends verify
{
	var $output;
	var $output2;
	
	function old__construct($section = 'profile') {
		
		global $path_site;
		global $meta_desc;
		global $meta_addtl;
		global $meta_keywords;
		global $title;
		global $_REQUEST;
		global $states;
		global $ccExpMM;
		global $ccExpYY;
		global $cl;
		global $profnav;
		@ $method = $_REQUEST['method'];
		@ $action = $_REQUEST['action'];
		@ $call = $_REQUEST['call']; // a call is an ajax call.
		
		// SETUP PAGE OUTPUT VARIABLES START \\
		$returnmsg = NULL;
		$disabled = NULL;
		$returnmsg_addAddr = NULL;
		$contentOutput = NULL;
		
		// PROFILE HOME \\
		if($section=='profile')
		{
			// PROF LEFT NAV ACTIVE START \\
			$profnav['acctinfo'] = 'active';
			// PROF LEFT NAV ACTIVE END \\

			if(!isset($_SESSION['user']['id'])) { redirect($path_site.'profile/login.php'); }
			
			// INIT OBJECTS \\
			$returnmsg_prof = NULL;
						
			if(isset($_REQUEST['submitupdate']))
			{
				// check for errors
				if($errors = $this->checkProfile($_REQUEST))
				{
					$returnmsg_prof = replace_output(ERROR_PROFILE2,array('errors' => $errors));
				}
				else
				{
					// no errors, Login user and start session.
					if($this->submitUser($_REQUEST,'profile'))
					{
						// move user back to last
						$returnmsg_prof = CONF_MSG_1;
						unset($_REQUEST);
					}
					else
					{
						$returnmsg_prof = ERROR_MSG_CONTACT2;
					}
				}
			}

			// Get Profile Information
			$info = get_profile($_SESSION['user']['id']);
			// check if newsletter is selected.
			@($info['agreeNewsletter'] == 1)? $data_nl = ' checked="checked"' : $data_nl = NULL ;
			$txtStates = NULL;
			$addStates = NULL;
				foreach ($states as $key => $value) 
				{
					$sel = NULL; $selAdd = NULL;
					if(stripslashes($info['state']) == $key) { $sel = ' selected="selected"'; }
					if(@$_REQUEST['addState'] == $key) { $selAdd = ' selected="selected"'; }
					$txtStates .= '<option value="' .$key. '"' .$sel. '>' .$value. '</option>';
					$addStates .= '<option value="' .$key. '"' .$selAdd. '>' .$value. '</option>';
				}
							
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctProfile.inc';
			$returnOutput = new main_output($content);
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
			'path_site' => $path_site,
			'return_msg' => $returnmsg_prof,
			'data_un' => stripslashes($info['username']),
			'data_em' => stripslashes($info['email']),
			'data_fn' => stripslashes($info['firstName']),
			'data_mi' => stripslashes($info['mi']),
			'data_ln' => stripslashes($info['lastName']),
			'data_addr1' => stripslashes($info['addr1']),
			'data_addr2' => stripslashes($info['addr2']),
			'data_city' => stripslashes($info['city']),
			'states' => $txtStates,
			'data_zc1' => substr(stripslashes($info['zip']),0,5),
			'data_zc2' => substr(stripslashes($info['zip']),5,4),
			'data_phone1' => substr(stripslashes($info['mainPhone']),0,3),
			'data_phone2' => substr(stripslashes($info['mainPhone']),3,3),
			'data_phone3' => substr(stripslashes($info['mainPhone']),6,4),
			'data_phone4' => substr(stripslashes($info['altPhone']),0,3),
			'data_phone5' => substr(stripslashes($info['altPhone']),3,3),
			'data_phone6' => substr(stripslashes($info['altPhone']),6,4),
			'data_nl' => $data_nl,
			'site_baseurl' => SITE_BASEURL_SECURE
			));
			// Call the output
			$contentOutput .= $returnOutput->output;
			
			// GET TEMP HEADER
			$temp_notif_output = get_profile_notif();
			
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/tpl/profile_body.inc';
			$returnOutput = new main_output($content);
			// replace tags from template
			@ $returnOutput->replace_tags(array(	
				'path_site' => SITE_BASEURL_SECURE,
				'site_baseurl' => SITE_BASEURL_SECURE,
				'return_msg' => $returnmsg,
				'profile_nav' => $cl->compile_profileNav($profnav),
				'box_four' => $temp_notif_output.replace_output(
					file_get_contents($path_site.'inc/cb/box_four.inc'),
					array(
						'path_site' => SITE_BASEURL_SECURE,
						'header_style' => 'nav',
						'list' => replace_output(HH_PM_PROF_NAV1, array('path_site' => SITE_BASEURL_SECURE)),
						'content' => $contentOutput
					)
				)
			));
			// Call the output
			$this->output .= $returnOutput->output;
			
		}

		// PROFILE FORGOT \\
		else if($section == 'forgot')
		{
			// see if someone visits the registration page, but it already logged in, send them to thier profile homepage
			if(isset($_SESSION['user']['id'])) { redirect($path_site.'profile/'); }
			
			// else, now check if they're registering or we're processing a registration.
			$returnmsg = NULL;
			if(isset($_REQUEST['submitforgot']))
			{
				// check for errors
				if($errors = $this->checkForgot($_REQUEST))
				{
					$returnmsg = replace_output(ERROR_PROFILE2,array('errors' => $errors));
				}
				else
				{
					// no errors, Login user and start session.
					if($this->submitUser($_REQUEST,'forgot'))
					{
						// move user back to last
						$returnmsg = CONF_PROFILE_FORGOT1;
						$meta_addtl .= replace_output(META_REDIRECT,array('path_site' => $path_site,'secs'=>'10'));
						$disabled = ' disabled="disabled"';
						unset($_REQUEST['email']);
						
					}
					else
					{
						$returnmsg = ERROR_PROFILE_FORGOT1;
					}
				}
			}
			// landing page for forgot
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctForgot.inc';
			$returnOutput = new main_output($content);
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
			'path_site' => $path_site,
			'disabled' => $disabled,
			'return_msg' => $returnmsg,
			'data_em' => $_REQUEST['user_email']
			));
			// Call the output
			$this->output = $returnOutput->output;
		}


		// PROFILE LOGIN
		else if ($section == 'login') 
		{
		
			// SETUP PAGE OUTPUT VARIABLES START
			# the response from reCAPTCHA
			$resp = null;
			# the error code from reCAPTCHA, if any
			$error = null;
			
			// see if someone visits the registration page, but it already logged in, send them to thier profile homepage
			if(isset($_SESSION['user']['id'])) { redirect($path_site.'profile/'); }
			// else, now check if they're registering or we're processing a registration.
			$returnmsg = null;
			
			if (isset($_REQUEST['submit_login'])) 
			{
				// check for errors
				if ($errors = $this->checkLogin($_REQUEST)) {
					$returnmsg = replace_output(ERROR_PROFILE1,array('errors' => $errors));
				} else {
					// no errors, Login user and start session.
					if ($this->submitUser($_REQUEST,'login')) {
						// move user back to last
						// print $last;
						if (isset($_SESSION['last'])) { redirect($_SESSION['last']); } else { redirect(SITE_BASEURL); }
						unset($_REQUEST, $_SESSION['last']);
						exit();
					} else {
						$returnmsg = ERROR_EMAIL_VERIFICATION;
					}
				}
			}
									
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctLogin.inc';
			$returnOutput = new main_output($content);
			
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
				'path_site' => $path_site,
				'return_msg' => $returnmsg,
				'site_title' => SITE_TITLE,
				'captcha' => recaptcha_get_html(CX_CAPTCHA_PUBLIC_KEY, $error,TRUE),
				'data_un' => $_REQUEST['username']
			));
			
			
			
			
			
			
			
			
			// Call the output
			$contentOutput .= $returnOutput->output;
			
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/box_four.inc';
			$returnOutput = new main_output($content);
			
			// replace tags from template
			@ $returnOutput->replace_tags(array(	
				'path_site' => SITE_BASEURL_SECURE,
				'site_baseurl' => SITE_BASEURL_SECURE,
				'return_msg' => $returnmsg,
				'header_style' => 'banner',
				'list' => replace_output(HH_PM_DHDR_LOGIN_NAV1, array('path_site' => SITE_BASEURL_SECURE)),
				'content' => $contentOutput
			));
			
			// Call the output
			$this->output .= $returnOutput->output;

			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctLogin_tip.inc';
			$returnOutput = new main_output($content);

			// replace tags from template
			@ $returnOutput->replace_tags(array(	
				'path_site' => SITE_BASEURL_SECURE
			));
			
			// Call the output
			$this->output2 .= $returnOutput->output;
		}
		
		// PROFILE LOGOUT \\
		else if($section == 'logout')
		{
			session_destroy();
			redirect(SITE_BASEURL);
		}

		// PROFILE REGISTER
		else if ($section == 'register') 
		{
			// VERIFICATION
			if (isset($_REQUEST['verify'])) {
				if($this->submitUser($_REQUEST, 'verify')) { redirect(SITE_BASEURL_SECURE.'profile/'); }
				else { redirect(SITE_BASEURL_); }
			}
			//CONFIRM
			if($action == 'confirm')
			{
				if($_SESSION['registration']['complete'] && $_SESSION['registration']['type'] == 'regular')
				{
					// CALL CONTENT AND REPLACE TAGS INSIDE
					$content = $path_site.'inc/cb/acctRegister_confirm.inc';
					$returnOutput = new main_output($content);
					// replace tags from template
					@ $returnOutput->replace_tags(array(	
						'path_site' => SITE_BASEURL_SECURE,
						'returnmsg' => $returnmsg,
					));
					// Call the output
					$contentOutput .= $returnOutput->output;
					unset($_SESSION['registration']);
				}
				else { redirect(SITE_BASEURL_SECURE.'profile/'); }
			}
			else
			{
				// see if someone visits the registration page, but it already logged in, send them to thier profile homepage
				if(isset($_SESSION['user']['id'])) { redirect($path_site.'profile/'); }
			
				// SETUP PAGE OUTPUT VARIABLES START
				# the response from reCAPTCHA
				$resp = null;
				# the error code from reCAPTCHA, if any
				$error = null;
				
				(isset($_REQUEST['agree']) && $_REQUEST['agree'] == '1') ? $data_ag = ' checked="checked"' : $data_ag = null ; 
				// (isset($_REQUEST['newsletter']) and $_REQUEST['newsletter'] == '1')? $data_nl=' checked="checked"' : $data_nl=NULL ; 
				// SETUP PAGE OUTPUT VARIABLES END
							
				// Check if they're registering or we're processing a registration.
				if (isset($_REQUEST['submit'])) 
				{
					// check for errors
					$errors = $this->checkReg($_REQUEST);
					if ($errors) {
						$returnmsg = replace_output(ERROR_PROFILE1, array('errors' => $errors));
					}
					else {
						// no errors, add user to database, and start session.
						if($this->submitUser($_REQUEST, 'reg'))
						{
							$_SESSION['registration']['complete'] = TRUE;
							$_SESSION['registration']['type'] = 'regular';
							unset($_REQUEST);
							redirect(SITE_BASEURL_SECURE.'profile/register.php?action=confirm');
							exit();
						}
						else { $returnmsg = ERROR_MSG_CRITICAL1; }
					}
				}
				// CALL CONTENT AND REPLACE TAGS INSIDE
				$content = $path_site.'inc/cb/acctRegister.inc';
				$returnOutput = new main_output($content);
				// replace tags from template
				@ $returnOutput->replace_tags(array(	
					'path_site' => $path_site,
					'return_msg' => $returnmsg,
					// 'states' => $txtStates,
					'data_un' => $_REQUEST['user_name'],
					'data_em' => $_REQUEST['user_email'],
					'data_fn' => $_REQUEST['user_fname'],
					'data_ln' => $_REQUEST['user_lname'],
					/*'data_mi' => $_REQUEST['user_mi'],
					'data_addr1' => $_REQUEST['user_addr1'],
					'data_addr2' => $_REQUEST['user_addr2'],
					'data_city' => $_REQUEST['user_city'],
					'data_state' => $_REQUEST['user_state'],
					'data_zc1' => $_REQUEST['user_zc1'],
					'data_zc2' => $_REQUEST['user_zc2'],
					'data_phone1' => $_REQUEST['user_phone1'],
					'data_phone2' => $_REQUEST['user_phone2'],
					'data_phone3' => $_REQUEST['user_phone3'],
					'data_phone4' => $_REQUEST['user_phone4'],
					'data_phone5' => $_REQUEST['user_phone5'],
					'data_phone6' => $_REQUEST['user_phone6'],*/
					'data_ag' => $data_ag,
					// 'data_nl' => $data_nl,
					'site_title' => SITE_TITLE,
					'captcha' => recaptcha_get_html(CX_CAPTCHA_PUBLIC_KEY, $error, TRUE),
					'disabled' => $disabled
				));
				// Call the output
				$contentOutput .= $returnOutput->output;
			}
			
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/box_four.inc';
			$returnOutput = new main_output($content);

			// replace tags from template
			@ $returnOutput->replace_tags(array(	
				'path_site' => SITE_BASEURL_SECURE,
				'site_baseurl' => SITE_BASEURL_SECURE,
				'return_msg' => $returnmsg,
				'header_style' => 'banner',
				'list' => replace_output(HH_PM_DHDR_REG_NAV1, array('path_site' => SITE_BASEURL_SECURE)),
				'content' => $contentOutput
			));
			
			// Call the output
			$this->output .= $returnOutput->output;
			
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctRegister_tip.inc';
			$returnOutput = new main_output($content);

			// replace tags from template
			@ $returnOutput->replace_tags(array(	
				'path_site' => SITE_BASEURL_SECURE
			));
			
			// Call the output
			$this->output2 .= $returnOutput->output;
			
		}
		
		// PUBLIC PROFILE INFO \\
		else if($section=='pubProf')
		{	
			// PROF LEFT NAV ACTIVE START \\
			$profnav['pubprof'] = 'active';
			// PROF LEFT NAV ACTIVE END \\
		
			if(!isset($_SESSION['user']['id'])) { redirect($path_site.'profile/login.php'); }
			
			// INIT OBJECTS \\
			$returnmsg_pp = NULL;
						
			if(isset($_REQUEST['submitupdate']))
			{
				// check for errors
				if($errors = $this->check('pubProf'))
				{
					$returnmsg_pp = replace_output(ERROR_PROFILE2,array('errors' => $errors));
				}
				else
				{
					// no errors
					if($this->submit('pubProf'))
					{
						$returnmsg_pp = CONF_MSG_1;
						unset($_REQUEST);
					}
					else
					{
						$returnmsg_pp = ERROR_MSG_CONTACT2;
					}
				}
			}

			// Get Profile Information
			$info = get_public_profile($_SESSION['user']['id']);
			$image = get_public_profile_image($info['id']);
			$c = get_colors();
			$colors = NULL;
				foreach ($c as $k => $v) 
				{
					if($k == '5') { $colors .= '</div><div class="left" style="width: 70px;">'; }
					$sel = NULL; 
					if($info['colorid'] == $k) { $sel = ' checked="checked"'; }
					$colors .= replace_output(HH_PM_PUBPROF_COLOR,array('check'=>$sel,'id'=>$k,'class'=>$v));
				}
							
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/cb/acctPublic_profile.inc';
			$returnOutput = new main_output($content);
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
			'path_site' => SITE_BASEURL_SECURE,
			'return_msg' => $returnmsg_pp,
			'data_img' => SITE_BASEURL_SECURE.$image, //$_REQUEST['url'],
			//'data_url' => stripslashes($info['urlSite']), //$_REQUEST['url'],
			'data_blogurl' => stripslashes($info['urlBlog']),//$_REQUEST['blogurl'],
			'data_fburl' => stripslashes($info['urlFb']), //$_REQUEST['fburl'],
			'data_liurl' => stripslashes($info['urlLi']),//$_REQUEST['liurl'],
			'data_twtrurl' => stripslashes($info['urlTwtr']),//$_REQUEST['twtrurl'],
			'data_yturl' => stripslashes($info['urlYt']),
			'data_stmnt' => stripslashes($info['statement']),//$_REQUEST['statement'],
			'data_name' => stripslashes($info['name']), //$_REQUEST['name'],
			'data_title' => stripslashes($info['title']),//$_REQUEST['title'],
			'data_colors' => $colors
			));
			// Call the output
			$contentOutput .= $returnOutput->output;
			
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$content = $path_site.'inc/tpl/profile_body.inc';
			$returnOutput = new main_output($content);
			
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
				'path_site' => SITE_BASEURL_SECURE,
				'site_baseurl' => SITE_BASEURL_SECURE,
				'return_msg' => $returnmsg,
				'profile_nav' => $cl->compile_profileNav($profnav),
				'box_four' => get_profile_notif().replace_output(
					file_get_contents($path_site.'inc/cb/box_four.inc'),
					array(
						'path_site' => SITE_BASEURL_SECURE,
						'header_style' => 'nav',
						'list' => replace_output(HH_PM_DHDR_PUBPROF_NAV, array('path_site' => SITE_BASEURL_SECURE)),
						'content' => $contentOutput
					)
				),
			));
			
			// Call the output
			$this->output .= $returnOutput->output;

			
		}


	}
	// get output function
	function get_output($type=NULL)
	{
		//$this->output .= compile_debug_array($this->output);
		if($type == 'tip')
		{
			return $this->output2;
		}
		else { return $this->output; }
	}
	
	// error checking
	function checkProfile($request)
	{
		$userid = $_SESSION['user']['id'];	

		$errors = FALSE;
		if (empty($request['user_email'])) { $errors .= 'The email address field cannot be empty.  Check Profile<br />'; }
		if (empty($request['user_name'])) { $errors .= 'The username field cannot be empty.<br />'; }
		if (!preg_match('/^[a-zA-Z0-9\._%\-]+@[a-zA-Z0-9.\_%\-]+\.[a-zA-Z0-9]{2,6}$/i', $request['user_email'])) { $errors .= 'The email address is not properly formatted (i.e. <em>youremail@yourdomain.com</em>).<br />'; }
		if (!preg_match('/^[[:space:]a-zA-Z0-9\'\.*#\/\\_;:\-]{6,30}$/i', $request['user_name'])) { $errors .= 'The User Name must have a minimum of 6 characters, with a maximum of 30;  And cannot contain certain special characters.<br />'; }

		if(isset($request['user_pass']) and !empty($request['user_pass']))
		{
			if (empty($request['user_pass2'])) { $errors .= 'The confirm password field cannot be empty.<br />'; }
			if (strlen($request['user_pass']) > 30 || strlen($request['user_pass']) < 6) { $errors .= 'The Password must have a minimum of 6 characters, with a maximum of 30.<br />'; }
			if (strcasecmp($request['user_pass'],$request['user_pass2']) <> 0) { $errors .= 'The Passwords submitted do not match.<br />'; }
		}
		// check if user_name is in use.
		if($request['user_name'] != $_SESSION['user']['username']){ if(check_username($request['user_name'])) { $errors .= 'The user name you have selected is already in use in our system.  Please chose another<br />'; } }
		//check if email address is in use.
		if($request['user_email'] != $_SESSION['user']['email']){ if(check_email($request['user_email'],$userid)) { $errors .= 'The email you have entered ( ' .$email. ' ) is already in use in our system.<br />'; } }

		// PROFILE and SHIPPING INFORMATION
		if (empty($request['user_fname'])) { $errors .= 'The first name field cannot be empty.<br />'; }
		if (empty($request['user_lname'])) { $errors .= 'The last name field cannot be empty.<br />'; }
		if (empty($request['user_addr1'])) { $errors .= 'The address field cannot be empty.<br />'; }
		if (empty($request['user_city'])) { $errors .= 'The city field cannot be empty.<br />'; }
		if (empty($request['user_state']) or $request['user_state'] == '0') { $errors .= 'Please select your state.<br />'; }
		if (empty($request['user_zc1'])) { $errors .= 'The zip code field cannot be empty.<br />'; }
		if (empty($request['user_phone1']) or empty($request['user_phone2']) or empty($request['user_phone3'])) { $errors .= 'The main phone number field cannot be empty.<br />'; }
		if (!preg_match('/^[0-9]{5,5}$/i', $request['user_zc1'])) { $errors .= 'The first zip code field is not properly formatted.  Must be 5 numbers only.<br />'; }
		if (!empty($request['user_zc2']) and !preg_match('/^[0-9]{4,4}$/i', $request['user_zc2'])) { $errors .= 'The second zip code field is not properly formatted.  Must be 5 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone1'])) { $errors .= 'The first main phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone2'])) { $errors .= 'The second main phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{4,4}$/i', $request['user_phone3'])) { $errors .= 'The third main phone number field is not properly formatted.  Must be 4 numbers only.<br />'; }
		if (!empty($request['user_phone4']) and !empty($request['user_phone5']) and !empty($request['user_phone6']))
		{
			if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone4'])) { $errors .= 'The first alt phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
			if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone5'])) { $errors .= 'The second alt phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
			if (!preg_match('/^[0-9]{4,4}$/i', $request['user_phone6'])) { $errors .= 'The third alt phone number field is not properly formatted.  Must be 4 numbers only.<br />'; }
		}

		// return
		return $errors;
	}
	function checkReg($request)
	{
		$resp = NULL;
		
		$errors = FALSE;
		if (empty($request['user_email'])) { $errors .= 'The email address field cannot be empty.<br />'; }
		if (empty($request['user_name'])) { $errors .= 'The username field cannot be empty.<br />'; }
		if (empty($request['user_pass'])) { $errors .= 'The password field cannot be empty.<br />'; }
		if (empty($request['user_pass2'])) { $errors .= 'The confirm password field cannot be empty.<br />'; }
		if (!preg_match('/^[a-zA-Z0-9\._%\-]+@[a-zA-Z0-9.\_%\-]+\.[a-zA-Z0-9]{2,6}$/i',$request['user_email'])) { $errors .= 'The email address is not properly formatted (i.e. <em>youremail@yourdomain.com</em>).<br />'; }
		if (!preg_match('/^[[:space:]a-zA-Z0-9\'\.*#\/\\_;:\-]{6,30}$/i', $request['user_name'])) { $errors .= 'The User Name must have a minimum of 6 characters, with a maximum of 30;  And cannot contain certain special characters.<br />'; }
		if (strlen($request['user_pass']) > 30 || strlen($request['user_pass']) < 6) { $errors .= 'The Password must have a minimum of 6 characters, with a maximum of 30.<br />'; }
		if (strcasecmp($request['user_pass'],$request['user_pass2']) <> 0) { $errors .= 'The Passwords submitted do not match.<br />'; }
		// check if username is in use.
		$un = $request['user_name'];
		$unlower = strtolower($request['user_name']);
		$unupper = strtoupper($request['user_name']);
		$unucfirst = ucfirst($request['user_name']);
		$unucwords = ucwords($request['user_name']);
		$query = "select id from user where (username = '" .$un. "' or username = '" .$unlower. "' or username = '" .$unupper. "' or username = '" .$unucfirst. "' or username = '" .$unucwords. "') and status <> 'deleted'";
		$result = mysql_query($query);
		if(mysql_fetch_row($result)) { $errors .= 'The username you have selected (' .$request['user_name']. ') is already in use in our system.  Please chose another<br />'; }
		//check if email address is in use.
		$email = strtolower($request['user_email']); 
		$query = "select id from user where email = '" .strtolower($request['user_email']). "' and status <> 'deleted'";
		$result = mysql_query($query);
		if(mysql_fetch_row($result)) { $errors .= 'The email you have entered ( ' .$request['user_email']. ' ) is already in use in our system. Please <a href="#">click here if you have forgot your login info</a><br />'; }
		// PROFILE and SHIPPING INFORMATION
		if (empty($request['user_fname'])) { $errors .= 'The first name field cannot be empty.<br />'; }
		if (empty($request['user_lname'])) { $errors .= 'The last name field cannot be empty.<br />'; }
		/*
		if (empty($request['user_addr1'])) { $errors .= 'The address field cannot be empty.<br />'; }
		if (empty($request['user_city'])) { $errors .= 'The city field cannot be empty.<br />'; }
		if (empty($request['user_state']) or $request['user_state'] == '0') { $errors .= 'Please select your state.<br />'; }
		if (empty($request['user_zc1'])) { $errors .= 'The zip code field cannot be empty.<br />'; }
		if (empty($request['user_phone1']) or empty($request['user_phone2']) or empty($request['user_phone3'])) { $errors .= 'The main phone number field cannot be empty.<br />'; }
		*/
		if (@$request['agree'] <> '1') { $errors .= 'Please agree to the ' .SITE_TITLE. ' terms of site usage.<br />'; }
		/*if (!preg_match('/^[0-9]{5,5}$/i', $request['user_zc1'])) { $errors .= 'The first zip code field is not properly formatted.  Must be 5 numbers only.<br />'; }
		if (!empty($request['user_zc2']) and !preg_match('/^[0-9]{4,4}$/i', $request['user_zc2'])) { $errors .= 'The second zip code field is not properly formatted.  Must be 5 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone1'])) { $errors .= 'The first main phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone2'])) { $errors .= 'The second main phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
		if (!preg_match('/^[0-9]{4,4}$/i', $request['user_phone3'])) { $errors .= 'The third main phone number field is not properly formatted.  Must be 4 numbers only.<br />'; }
		if (!empty($request['user_phone4']) and !empty($request['user_phone5']) and !empty($request['user_phone6']))
		{
			if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone4'])) { $errors .= 'The first alt phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
			if (!preg_match('/^[0-9]{3,3}$/i', $request['user_phone5'])) { $errors .= 'The second alt phone number field is not properly formatted.  Must be 3 numbers only.<br />'; }
			if (!preg_match('/^[0-9]{4,4}$/i', $request['user_phone6'])) { $errors .= 'The third alt phone number field is not properly formatted.  Must be 4 numbers only.<br />'; }
		}
		*/
		// CAPTCHA STUFF
		if (@$request["recaptcha_response_field"]) 
		{
			$resp = recaptcha_check_answer (CX_CAPTCHA_PRIVATE_KEY,
				$_SERVER["REMOTE_ADDR"],
				$request["recaptcha_challenge_field"],
				$request["recaptcha_response_field"]);
			if (!$resp->is_valid) {
					# set the error code so that we can display it
					$error = $resp->error;
					$errors .= 'The captcha confirmation box is incorrect.<br />';
				}
		}
		else { $errors .= 'The captcha confirmation box is blank and cannot be.<br />'; }
		
		// return
		return $errors;
	}
	function checkForgot($request)
	{
		$errors = FALSE;
		if (empty($request['user_email'])) { $errors .= 'The email field cannot be empty.<br />'; }
		if (!preg_match('/^[a-zA-Z0-9\._%\-]+@[a-zA-Z0-9.\_%\-]+\.[a-zA-Z0-9]{2,6}$/i', $request['user_email'])) { $errors .= 'The email address is not properly formatted (i.e. <em>Johndoe@domain.com</em>).<br />'; }
 		// return
		return $errors;
	}
	function checkLogin($request)
	{
		$errors = FALSE;
		if (empty($request['username'])) { $errors .= 'The Username field cannot be empty.<br />'; }
		if (empty($request['password'])) { $errors .= 'The Password field cannot be empty.<br />'; }
		if (!preg_match('/^[[:space:]a-zA-Z0-9\'\.*#\/\\_;:\-]{6,30}$/i', $request['username'])) { $errors .= 'The Username must have a minimum of 6 characters, with a maximum of 30;  And cannot contain certain special characters.<br />'; }
		if (strlen($request['password']) > 30 || strlen($request['password']) < 6) { $errors .= 'The Password must have a minimum of 6 characters, with a maximum of 30.<br />'; }
 		// return
		return $errors;
	}
	function checkStoreCreate()
	{
		global $_REQUEST;
		$errors = FALSE;
		@$check = $this->checkRadio($_REQUEST['type'],array('shop','service'),'Shop or Service selection'); if($check['error']) { $errors .= $check['errorMsg']; } 
		@$check = $this->checkTextField($_REQUEST['name']); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkTextField($_REQUEST['desc'],3,1000,'description'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkURLField($_REQUEST['url'],6,225,'website url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['url'] = $check['inputReturn']; }
		if(!empty($_REQUEST['blogurl'])) { @$check = $this->checkURLField($_REQUEST['blogurl'],6,225,'blog url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['blogurl'] = $check['inputReturn']; } }
		if(!empty($_REQUEST['fburl'])) { @$check = $this->checkURLField($_REQUEST['fburl'],6,225,'facebook url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['fburl'] = $check['inputReturn']; } }
		if(!empty($_REQUEST['twtrurl'])) { @$check = $this->checkURLField($_REQUEST['twtrurl'],6,225,'twitter url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['twtrurl'] = $check['inputReturn']; } }
		if(!empty($_REQUEST['liurl'])) { @$check = $this->checkURLField($_REQUEST['liurl'],6,225,'linkedIn url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['liurl'] = $check['inputReturn']; } }
		@$check = $this->checkEmail($_REQUEST['email'],'business','email'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkTextField($_REQUEST['addr1'],5,225,'1st Address'); if($check['error']) { $errors .= $check['errorMsg']; }
		if(!empty($_REQUEST['addr2'])) { @$check = $this->checkTextField($_REQUEST['addr2'],5,225,'2nd Address'); if($check['error']) { $errors .= $check['errorMsg']; } }
		@$check = $this->checkTextField($_REQUEST['city'],2,125,'city'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkState($_REQUEST['state'],2,2,'state'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkZip($_REQUEST['zc1'],$_REQUEST['zc2'],'zip'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkTextField($_REQUEST['ccName'],3,150,'Credit Card Name'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkCCnum($_REQUEST['ccNum'],15,16); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkCCexp($_REQUEST['ccExpMM'],$_REQUEST['ccExpYY']); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkNumberField($_REQUEST['ccSecCode'],3,4,'Credit Card Security Code'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkPhoneField1($_REQUEST['ccPhone'],10,12,'Credit Card Phone'); if($check['error']) { $errors .= $check['errorMsg']; }
		@$check = $this->checkCheckbox($_REQUEST['agree'],'Agree to '.SUPPLIER_TITLE.' terms'); if($check['error']) { $errors .= $check['errorMsg']; }
		
		// return
		return $errors;
	}
	function check($method='profile')
	{
		global $_REQUEST;
		global $_FILES;
		$errors = FALSE;
		
		if($method == 'pubProf')
		{
			if(!empty($_REQUEST['color'])) { @$check = $this->checkRadio($_REQUEST['color'],array('1','2','3','4','5','6','7','8'),'Color'); if($check['error']) { $errors .= $check['errorMsg']; } }
			if($_FILES['img']['error'] != 4) { @$check = $this->check_image_flexible_wh($_FILES['img'],'Image',1024000); if($check['error']) { $errors .= $check['errorMsg']; } }
			// if(!empty($_REQUEST['url'])) { @$check = $this->checkURLField($_REQUEST['url'],6,225,'website'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['url'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['blogurl'])) { @$check = $this->checkURLField($_REQUEST['blogurl'],6,225,'blog'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['blogurl'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['fburl'])) { @$check = $this->checkURLField($_REQUEST['fburl'],6,225,'facebook url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['fburl'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['twtrurl'])) { @$check = $this->checkURLField($_REQUEST['twtrurl'],6,225,'twitter url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['twtrurl'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['liurl'])) { @$check = $this->checkURLField($_REQUEST['liurl'],6,225,'linkedIn url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['liurl'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['yturl'])) { @$check = $this->checkURLField($_REQUEST['yturl'],6,225,'Youtube url'); if($check['error']) { $errors .= $check['errorMsg']; } if(@$check['inputReturn']) { $_REQUEST['yturl'] = $check['inputReturn']; } }
			if(!empty($_REQUEST['statement'])) { @$check = $this->checkTextField($_REQUEST['statement'],5,225,'Statement'); if($check['error']) { $errors .= $check['errorMsg']; } }
			if(!empty($_REQUEST['name'])) { @$check = $this->checkTextField($_REQUEST['name'],3,125); if($check['error']) { $errors .= $check['errorMsg']; } }
			if(!empty($_REQUEST['title'])) { @$check = $this->checkTextField($_REQUEST['title'],3,50,'Title'); if($check['error']) { $errors .= $check['errorMsg']; } }
		}
		// return
		return $errors;
	}
	
	//submit registration
	function submitUser($request,$method='reg')
	{
		$time=time(); 
		global $path_site;
		
		if($method == 'login')
		{
			// LOGIN USER
			$un = $request['username'];
			$unlower = strtolower($request['username']);
			$unupper = strtoupper($request['username']);
			$unucfirst = ucfirst($request['username']);
			$unucwords = ucwords($request['username']);
			$password = $request['password'];
			$query = "select id,password,email,zip from user where (username = '$un' or username = '$unlower' or username = '$unupper' or username = '$unucfirst' or username = '$unucwords') and status='active'";
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_row($result);
			if(pw_check($password, $row[1]))
			{
				// START SESSION
				set_session($row[0],$request['username'],stripslashes($row[2]),$row[3]);	
				//return
				return TRUE;			
			}
			else
			{
				return FALSE;
			}
		}
		else if($method == 'forgot')
		{
		
			// LOGIN USER
			$email = strtolower($request['user_email']);
			$tempPassword = uniqid(rand(0,9999999));
			$query = "update user set password='" .pw_encode($tempPassword). "' where email='" .$email. "' and status <> 'deleted'";
			if($result = mysql_query($query))
			{
				// SEND EMAIL, RETURN TRUE CHANGE IN PRODUCTION - THE HTTP HOST BELOW NEEDS TO BE UPDATED
				$query = "select username from user where email='" .$email. "' and status <> 'deleted'";
				$result = mysql_query($query) or die(mysql_error());
				$row = mysql_fetch_row($result);
				// SEND AN EMAIL TO USER
				$to= stripslashes($row[0]).'<' .$email. '>';
				$from = EMAIL_FORGOT_FROM;
				$subject = EMAIL_FORGOT_SUBJECT;
				// CALL CONTENT AND REPLACE TAGS INSIDE
				$template = $path_site.EMAIL_FORGOT_TEMPLATE;
				$returnOutput = new main_output($template);
				// replace tags from template
				@ $returnOutput->replace_tags( array(	
					'subject' => EMAIL_BUSINESS_REGISTER_SUBJECT,
					'username' => stripslashes($row[0]),
					'site_name' => SITE_NAME,
					'site_title' => SITE_TITLE,
					'temppassword' => $tempPassword,
					'path_site'=>SITE_BASEURL_SECURE
					));
				// Call the output
				$body = $returnOutput->output;
				// CALL SEND EMAIL
				// send_email($to,$subject,$body,$from);
				
				send_email($email,$subject,$body);
				
				//return
				return TRUE;			
			}
			else
			{
				//echo $query;
				return FALSE;
			}
		}
		else if($method == 'verify')
		{
			// LOGIN USER
			mysql_query("update user set status='active' where secToken='" .$request['verify']. "'") or die(mysql_error());
			$result = mysql_query("select id,username,email,zip from user where secToken='" .$request['verify']. "'") or die(mysql_error());
			$row = mysql_fetch_row($result);
			set_session($row[0],stripslashes($row[1]),stripslashes($row[2]),$row[3]);
			return TRUE;
		}
		else if ($method == 'profile')
		{	
			$userid = $_SESSION['user']['id'];
			$flag_nl = 0; 
			if(isset($request['newsletter']) and ($request['newsletter'] == 'on' or $request['newsletter'] == 1) ) { $flag_nl = 1; }
			// check if old password was selected.
			$query_pw = NULL;
			if(isset($request['user_pass2']) and !empty($request['user_pass2']))
			{
				$query_pw = "password = '" .pw_encode($request['user_pass2']). "', ";
			}
			$query = "
				update user set
					email = '" .addslashes(strtolower($request['user_email'])). "',
					username = '" .addslashes($request['user_name']). "',
					firstName = '" .addslashes($request['user_fname']). "',
					mi = '" .addslashes($request['user_mi']). "',
					lastName = '" .addslashes($request['user_lname']). "',
					addr1 = '" .addslashes($request['user_addr1']). "',
					addr2 = '" .addslashes($request['user_addr2']). "',
					city = '" .addslashes($request['user_city']). "',
					state = '" .addslashes($request['user_state']). "',
					zip = '" .addslashes($request['user_zc1']).addslashes($request['user_zc2']). "',
					mainPhone = '" .addslashes($request['user_phone1'].$request['user_phone2'].$request['user_phone3']). "',
					altPhone = '" .addslashes($request['user_phone4'].$request['user_phone5'].$request['user_phone6']). "',					
					" .$query_pw. "
					flag_nl = '" .$flag_nl. "' 
				WHERE id = '" .$userid. "'
			";
			mysql_query($query) or die(mysql_error());
			return TRUE;
		}
		else if ($method == 'reg')
		{	
			global $path_site;
			$flag_agree = 0; // get agree flag
			if(isset($request['agree']) and ($request['agree'] == 'on' or $request['agree'] == '1')) { $flag_agree = 1; }
			// INSERT VALUES
			/*$query = "
				insert into user set
					username = '" .addslashes($request['user_name']). "',
					email = '" .addslashes(strtolower($request['user_email'])). "',
					password = '" .pw_encode($request['user_pass']). "',
					firstName = '" .addslashes($request['user_fname']). "',
					mi = '" .addslashes($request['user_mi']). "',
					lastName = '" .addslashes($request['user_lname']). "',
					addr1 = '" .addslashes($request['user_addr1']). "',
					addr2 = '" .addslashes($request['user_addr2']). "',
					city = '" .addslashes($request['user_city']). "',
					state = '" .addslashes($request['user_state']). "',
					zip = '" .addslashes($request['user_zc1']).addslashes($request['user_zc2']). "',
					mainPhone = '" .addslashes($request['user_phone1'].$request['user_phone2'].$request['user_phone3']). "',
					altPhone = '" .addslashes($request['user_phone4'].$request['user_phone5'].$request['user_phone6']). "',
					flag_nl = '" .$flag_nl. "' ,
					flag_tosu = '" .$flag_agree. "',
					dateReg = '" .$time. "'
			";*/
			$query = "
				insert into user set
					username = '" .addslashes($request['user_name']). "',
					email = '" .addslashes(strtolower($request['user_email'])). "',
					password = '" .pw_encode($request['user_pass']). "',
					firstName = '" .addslashes($request['user_fname']). "',
					lastName = '" .addslashes($request['user_lname']). "',
					flag_tosu = '" .$flag_agree. "',
					dateReg = '" .$time. "'
			";
			mysql_query($query) or die(mysql_error());
			$userid = mysql_insert_id();
			// Add a record into pubProfile
			$query = "
				insert into public_profile set
					name = '" .addslashes($request['user_fname']). "',
					userid='" .$userid. "'
			";
			mysql_query($query) or die(mysql_error());
			
			// INSERT INTO USERS PERMISSIONS
			$query = "
				insert into user_groups set
					userid='" .$userid. "'
			";
			mysql_query($query) or die(mysql_error());
			//INSERT SECURITY TOKEN
			$secToken = sha1(time().rand(0,9999999).$userid);
			mysql_query("update user set secToken = '" .$secToken. "' where id='" .$userid. "'") or die(mysql_error());

			// NEW
			// set_session($userid,$request['user_name'],strtolower($request['user_email']));
			if(isset($_REQUEST['l']) and $_REQUEST['l']='c') { $_SESSION['user']['id'] = $userid; }

			//send verification email.
			$to= $request['user_name'].'<' .$request['user_email']. '>';
			$from = EMAIL_REGISTER_FROM;
			$subject = EMAIL_REGISTER_SUBJECT;
			
			// email for registration
			// CALL CONTENT AND REPLACE TAGS INSIDE
			$template = $path_site.EMAIL_REGISTER_TEMPLATE;
			$returnOutput = new main_output($template);
			// replace tags from template
			@ $returnOutput->replace_tags( array(	
				'subject' => EMAIL_REGISTER_SUBJECT,
				'username' => $request['user_name'],
				'site_name' => SITE_NAME,
				'site_title' => SITE_TITLE,
				'verifyURL' => EMAIL_REGISTER_VERIFYLINK.$secToken
				));
			// Call the output
			$body = $returnOutput->output;
			// CALL SEND EMAIL
			send_email($request['user_email'],$subject,$body);

			return TRUE;
		}
	}
	function submit($method='profile')
	{
		global $_REQUEST;
		global $path_site;
		global $_FILES;
		$time=time(); 
		
		if($method == 'pubProf')
		{
			$query = "select id from public_profile where userid='" .$_SESSION['user']['id']. "'";
			$result = mysql_query($query) or die(mysql_error());
			$row = mysql_fetch_assoc($result);
			$pubid = $row['id'];
			// UPDATE PUBPROF INFO!
			$query = "update public_profile set
				`colorid`='" .$_REQUEST['color']. "',
				`urlBlog`='" .addslashes($_REQUEST['blogurl']). "',
				`urlFb`='" .addslashes($_REQUEST['fburl']). "',
				`urlTwtr`='" .addslashes($_REQUEST['twtrurl']). "',
				`urlLi`='" .addslashes($_REQUEST['liurl']). "',
				`urlYt`='" .addslashes($_REQUEST['yturl']). "'
				where `id`='" .$pubid. "'
			";
			/* OLD QUERY
			$query = "update public_profile set
				`colorid`='" .$_REQUEST['color']. "',
				`urlSite`='" .addslashes($_REQUEST['url']). "',
				`urlBlog`='" .addslashes($_REQUEST['blogurl']). "',
				`urlFb`='" .addslashes($_REQUEST['fburl']). "',
				`urlTwtr`='" .addslashes($_REQUEST['twtrurl']). "',
				`urlLi`='" .addslashes($_REQUEST['liurl']). "',
				`urlYt`='" .addslashes($_REQUEST['yturl']). "'
				where `id`='" .$pubid. "'
			";
			*/
			mysql_query($query) or die(mysql_error());
			// ADD IMAGE TO DB
				if($_FILES['img']['error'] == 0) 
				{ 
				$secToken = uniqid('pp_' .rand(0,99999));
				$filepath = 'img/pp/';
				$info = getimagesize($_FILES["img"]["tmp_name"]);
				$width = $info[0];
				$height = $info[1];
				switch ($info['mime']) { 
					case "image/gif": 
						$ext = ".gif"; 
						break; 
					case "image/jpeg": 
						$ext = ".jpg";
						break; 
					case "image/jpg": 
						$ext = ".jpg";
						break; 
					case "image/png": 
						$ext = ".png";
						break; 
				} 
				$file = $secToken.$ext;
				$tmp_name = $_FILES["img"]["tmp_name"];
				$new_image = imagecreatetruecolor(110, 130);
				if( $ext == '.jpg') 
				{
					$src = imagecreatefromjpeg($tmp_name);
					imagecopyresampled($new_image,$src, 0, 0, 0, 0, 110, 130, $width, $height);
					if(!imagejpeg($new_image,$path_site.$filepath.$file,75)) { return FALSE; } 
				} 
				else if ( $ext == '.png' ) 
				{ 
					$src = imagecreatefrompng($tmp_name); 
					imagealphablending($new_image, false);
					imagecolortransparent($new_image, imagecolorallocate($new_image, 255, 255, 255));
					imagesavealpha($new_image, true);
					imagecopyresampled($new_image,$src, 0, 0, 0, 0, 110, 130, $width, $height);
					if(!imagepng($new_image,$path_site.$filepath.$file,1)) { return FALSE; }
				}
				else 
				{
					$src = imagecreatefromgif($tmp_name); 
					imagealphablending($new_image, false);
					$transindex = imagecolortransparent($src);
					if($transindex >= 0) {
						$transcol = imagecolorsforindex($src, $transindex);
						$transindex = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
						imagefill($new_image, 0, 0, $transindex);
					}
					imagecopyresampled($new_image,$src, 0, 0, 0, 0, 110, 130, $width, $height);
					if($transindex >= 0) {
						imagecolortransparent($new_image, $transindex);
						for($y=0; $y<130; ++$y)
							for($x=0; $x<110; ++$x)
							  if(((imagecolorat($new_image, $x, $y)>>24) & 0x7F) >= 100) imagesetpixel($new_image, $x, $y, $transindex);
					}
					imagetruecolortopalette($new_image, true, 255);
					imagesavealpha($new_image, true);
					if(!imagegif($new_image,$path_site.$filepath.$file)) { return FALSE; } 
					
				}

				// SEE IF RECORD - ADD IMAGE TO PRODUCT TABLE
				$result = mysql_query("select item from images where itemid='" .$pubid. "'");
				if(mysql_num_rows($result) < 1){ $first = "insert into "; $last=NULL; } else {  $first = "update "; $last = " where itemid='" .$pubid. "'";  }
				$query = $first."`images` set
						`item`='pubProf',
						`itemid`='" .$pubid. "',
						`filepath`='" .$filepath. "',
						`file`='" .$file. "'
					".$last;
				mysql_query($query) or die(mysql_error());
			}
			
			return TRUE;

		}
	}

	// get order history
	function get_catOptions($method=NULL)
	{
		if($method == 'service')
		{
			$return = replace_output(HH_SELECT_OPTION1, array('id' => '','name'=>' - Select Category -'));
			$query = "select id,name from categories where status='active' and name='services' order by name asc";
			$result = mysql_query($query) or die(__LINE__.' - '.mysql_error());
			while($row = mysql_fetch_row($result))
			{
				$return.= replace_output(HH_SELECT_OPTION1, array('id' => $row[0],'name'=>stripslashes($row[1])));
			}
		}
		
		return $return;
	}
	function get_colOptions($id)
	{
		$return= replace_output(HH_SELECT_OPTION1, array('id' => '','name'=>' - Select Collection -'));
		
		$query = "select rcac.id as id, col.name as name from rel_cats_cols rcac 
						left join categories cat on rcac.catid=cat.id 
						left join collections col on rcac.colid=col.id
							where cat.id='" .$id. "'
							order by col.sortOrder asc, col.name asc";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_assoc($result))
		{
			$return .= replace_output(HH_SELECT_OPTION1, array('id' => $row['id'],'name'=>htmlentities(stripslashes($row['name']))));
		}
		return $return;
	}
	function get_asstOptions($id)
	{
		$return= replace_output(HH_SELECT_OPTION1, array('id' => '','name'=>' - Select Assortment -'));
		
		$query = "select rca.id as id, asst.name as name from rel_cac_assts rca 
					join rel_cats_cols rcac on rca.cacid=rcac.id 
					left join categories cat on rcac.catid=cat.id
					left join collections col on rcac.colid=col.id
					left join assortments asst on rca.asstid = asst.id
						where rca.cacid='" .$id. "'
						order by asst.sortOrder asc, asst.name asc";
					
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_assoc($result))
		{
			$return .= replace_output(HH_SELECT_OPTION1, array('id' => $row['id'],'name'=>htmlentities(stripslashes($row['name']))));
		}
		return $return;
	}
	
}


