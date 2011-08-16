<?php show_view('header'); ?>
<div id="wrapper-container">
    <div id="wrapper">
    
    <?php do_action('notifications'); ?>
    
<table cellpadding="0" cellspacing="0" border="0" class="registertable">
    <tbody><tr>
        <td valign="top" class="registerleft">
		    <div class="introduction">
		        <h1>Get Connected!</h1>
		        <ul id="featurelist">
		            <li>Connect and expand your network</li>
		            <li>View profiles and add new friends</li>
		            <li>Share your photos and videos</li>
		            <li>Create your own group or join others</li>
		        </ul>
				<div class="joinbutton">
					<a id="joinButton" href="<?php echo Router::url(array('controller' => 'user', 'action' => 'register')); ?>" title="JOIN US NOW, IT'S FREE!">
					    GET STARTED TODAY, IT'S FREE!</a>
				</div>
			</div>
        </td>
        <td width="200">
		    <div class="loginform">
		    	<form action="<?php echo Router::url(array('controller' => 'user', 'action' => 'login')); ?>" method="post" name="login" id="form-login">
		        	<h2>Login</h2>
		            <label>
						Username or Email<br>
		                <input type="text" value="<?php echo BRequest::getVar('username'); ?>" class="inputbox frontlogin" name="username" id="username">
		            </label>
										
		            <label>
						Password<br>
		                <input type="password" class="inputbox frontlogin" name="passwd" id="password">
		            </label>
										
					<label for="remember">
						<input type="checkbox" alt="Remember me" value="yes" id="remember" name="remember">
						Remember me
					</label>
					
					<div style="text-align: center; padding: 10px 0 5px;">
					    <input type="submit" value="Login" name="submit" id="submit" class="button">
					</div>
					
					<span>
						<a href="/component/user/reset.html" class="login-forgot-password"><span>Forgot your Password?</span></a><br>
						<a href="/component/user/remind.html" class="login-forgot-username"><span>Forgot your Username?</span></a>
					</span>
					<br>
					<a href="/community/register/activation.html" class="login-forgot-username">
						<span>Resend activation code?</span>
					</a>
		        </form>
		        
		    </div>
        </td>
    </tr></tbody>
</table>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php show_view('footer'); ?>