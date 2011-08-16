<?php show_view('header-w-wrap'); ?>
<?php show_view('sidebar-profile-left')?>

<div class="profile-content">
	
	{return_msg}
	<h1>Social Information</h1>
	
	<form action="<?php echo Router::url(array('controller' => 'user', 'action' => 'social')); ?>" enctype="multipart/form-data" method="post">
	
	<p>choose an image for your profile:</p>
	<div class="profile-image-settings left">
	    <p>This is the image that will show up in your public profile. The image you upload will be resized to fit 110px width by 130px height. Try to match your image uploaded as close to those dimensions as possible, Max upload width and height are 2000px by 1500px. You can upload a JPG, or GIF(file size limit is 1mb). Uploading a new image will replace the older one.<br /><br />Not happy with your photo/image? Want to edit? <a href="http://www.picresize.com" target="_blank">Manipulate it here</a></p>
	    <div class="profile-image-frame"></div>
	    <br/><input name="avatar" type="file" /><br/><br/>
	</div>
	
	<div class="clear"></div>
	<hr class="dotted" />
	
	<p>add your social media connections and online presence:</p>
	<dl class="social_profile">
	    <dt style="margin-top: 0px;"></dt>
	    <dd><input type="text" maxlength="225" name="urlSite" class="text-input medium-input" value="<?php echo $user->get('urlSite'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0px;"><img src="{path_site}img/icon_blog.png" alt="Your blog" width="25" height="25" /></dt>
	    <dd><input type="text" maxlength="225" name="urlBlog" class="text-input medium-input" value="<?php echo $user->get('urlBlog'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0;"><img src="{path_site}img/icon_twitter.png" alt="Your Twitter" width="25" height="25" /></dt>
	    <dd><input type="text" maxlength="225" name="urlTwtr" class="text-input medium-input" value="<?php echo $user->get('urlTwtr'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0;"><img src="{path_site}img/icon_linkedin.png" alt="Your LinkedIn" width="25" height="25" /></dt>
	    <dd><input type="text" maxlength="225" name="urlLi" class="text-input medium-input" value="<?php echo $user->get('urlLi'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0;"><img src="{path_site}img/icon_facebook.png" alt="Your Facebook" width="25" height="25" /></dt>
	    <dd><input type="text" maxlength="225" name="urlFb" class="text-input medium-input" value="<?php echo $user->get('urlFb'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 1px;"><img src="{path_site}img/icon_youtube.png" alt="Your Youtube" width="25" height="25" /></dt>
	    <dd><input type="text" maxlength="225" name="urlYt" class="text-input medium-input" value="<?php echo $user->get('urlYt'); ?>" /></dd>
	    <div class="clear"></div>
	</dl>
	<button name="submitupdate" type="submit" class="sexy-button sexy-simple sexy-large sexy-pm-brown">update profile</button>
	
	</form>

</div>

<?php show_view('footer-w-wrap'); ?>