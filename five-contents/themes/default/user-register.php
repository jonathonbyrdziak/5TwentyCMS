<?php show_view('header'); ?>
<div id="wrapper-container">
    <div id="wrapper">
    
    <?php do_action('notifications'); ?>
    
<table cellpadding="0" cellspacing="0" border="0" class="registertable">
    <tbody><tr>
        <td valign="top" class="registerleft">
		    <div class="introduction">
		        <h1>I Agree That..</h1>
		        <ul id="featurelist">
		            <li>I am at least 18 years of age</li>
		            <li>I accept the Portland Saturday Market Terms & Conditions and Privacy Policy</li>
		            <li>I can receive communications from Portland Saturday Market via email, and manage my terms of notification at anytime</li>
		        </ul>
			</div>
        </td>
        <td width="200">
		    <div class="loginform">
		    	<form action="<?php echo Router::url(array('controller' => 'user', 'action' => 'register')); ?>" method="post" name="login" id="form-login">
		        	<h2>Start Here</h2>
		            <label>
						Name<br>
		                <input type="text"  value="<?php echo BRequest::getVar('name'); ?>"class="inputbox frontlogin" name="name" id="name">
		            </label>
					
		            <label>
						Email<br>
		                <input type="text" value="<?php echo BRequest::getVar('email'); ?>" class="inputbox frontlogin" name="email" id="email">
		            </label>
					
					<label for="remember">
						<input type="checkbox" value="yes" id="agree" name="agree">
						I Agree to the Terms of Use
					</label>
					
					<div style="text-align: center; padding: 10px 0 5px;">
					    <input type="submit" value="Login" name="submit" id="submit" class="button">
					</div>
					
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