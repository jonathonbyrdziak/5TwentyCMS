<script type="text/javascript"> 
	function textCounter(field, countfield, maxlimit)
	{
		// alert(countfield.innerHTML.length);
		if (field.value.length > maxlimit) // if too long...trim it!
			field.value=field.value.substring(0, maxlimit);
		else  // otherwise, update counter
			countfield.innerHTML = parseInt(maxlimit - field.value.length);
			
	}
	function clear_textarea(type)
	{
		if(type == 'desc')
		{
			CKEDITOR.instances.desc.setData( '', function()
			{
				this.checkDirty();    
			});
			document.getElementById("descCount").innerHTML = 140;
		}
		
	}

</script>

<form action="{path_site}user/business" method="post" enctype="multipart/form-data" name="business">
<h2>choose a header image for your business:</h2>
<div class="business-image-settings left">
	<?php if ($business->banner): ?>
    <div class="business-image-frame" style="margin-bottom: 12px;"><img src="{data_img}" border="0" /></div>
    <?php endif; ?>
    <input name="img" type="file" />
    <p>This is the image that will show up as your header in multiple areas. The image you upload will be resized to fit 467px width by 92px height. Match your image uploaded as close to those dimensions as possible. Max upload width and height are 2000px by 1500px. You can upload a JPG, or GIF(file size limit is 1mb). Uploading a new image will replace the older one.</p>
    <p>Not happy with your photo/image? Want to edit? <a href="http://www.picresize.com/?prewidth=467&preheight=92" target="_blank">Manipulate it here</a></p>
</div>
<hr class="dotted clear" />
<h2>choose a color and icon image for your business:</h2>
<div class="pro-colors left">
    <small>please choose one:</small>
    <div class="clear"></div>
    <div class="left" style="width: 70px;">
        {data_colors}
    </div>
</div>
<div class="profile-image-settings left business-thumbnail">
    <div class="profile-image-frame"><img src="{data_imgicon}" border="0" /></div>
    <input name="imgicon" type="file" />
    <p>This is the icon image that will show up in your business section. <br />The image you upload will be resized to fit 110px width by 130px height. Match your image uploaded as close to those dimensions as possible, Max upload width and height are 2000px by 1500px. You can upload a JPG, or GIF(file size limit is 1mb). Uploading a new image will replace the older one.</p>
    <p>Not happy with your photo/image? Want to edit? <a href="http://www.picresize.com/?prewidth=110&preheight=130" target="_blank">Manipulate it here</a></p>
</div>
<hr class="dotted clear" />
<p><input type="checkbox" name="franchise" value="1"{data_fr} />&nbsp;&nbsp;My small business is a Franchise</p>
<hr class="dotted" />
<h2>what does your company do?</h2>
<dl>
    <dt style="margin-top: 0;">company description</dt>
    <dd>
    	<textarea name="desc" id="desc" style="margin-bottom: 5px; ">{desc}</textarea>
		<strong><span id="descCount">140</span></strong> remaining characters (including web code you cannot see). &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="clear_textarea('desc');">Reset description field</a>
    </dd>
</dl><div class="clear"></div>
<div class="clear"></div>
        
        <script type="text/javascript">
			 CKEDITOR.replace( 'desc',
				{
					toolbar: 'Light',
					height: '80px',
					width: '498px'	
				});
			 
			<!-- START Update the counter with text length of editor HTML output-->
			var editor = CKEDITOR.instances.desc;
			//alert( CKEDITOR.instances.slogan.name );
			var limit = 140;
			//alert(parseInt(limit - editor.getData().length));
			document.getElementById("descCount").innerHTML = parseInt(limit - editor.getData().length);
			editor.on( 'key', function( evt ){
			   textCounter( { value : editor.getData() },document.getElementById("descCount"), limit );
			}, editor.element.$ );
			<!-- END Update the counter with text length of editor HTML output-->
			 
			<!-- START Whether content has exceeded the maximum characters-->
			var locked;
			editor.on( 'key', function( evt ){
			 
			   var currentLength = editor.getData().length,
				  maximumLength = limit;
			   if( currentLength >= maximumLength )
			   {
				  if ( !locked )
				  {
					 // Record the last legal content.
					 editor.fire( 'saveSnapshot' ), locked = 1;
									// Cancel the keystroke.
					 evt.cancel();
				  }
				  else
					 // Check after this key has effected.
					 setTimeout( function()
					 {
						// Rollback the illegal one.  
						if( editor.getData().length > maximumLength )
						   editor.execCommand( 'undo' );
						else
						   locked = 0;
					 }, 0 );
			   }
			} );		
		</script>
<hr class="dotted" />
<h2>your business information</h2>
<p>NOTE: The business address information must be on file with the credit card information below.</p>
<dl>
    <dt>company name</dt>
    <dd><input name="name" type="text" class="text-input medium-input" maxlength="125" value="{data_name}" {disabled} /></dd>
    <div class="clear"></div>
    <dt>PM url</dt>
    <dd><p style="margin-top:8px;">{path_site}/{data_pmurl}</p></dd>
    <div class="clear"></div>
    <dt>street address</dt>
    <dd><input name="addr1" type="text" id="addr1" class="text-input medium-input" maxlength="225" value="{data_addr1}" {disabled} /></dd>
    <div class="clear"></div>
    <dt></dt>
    <dd class="addr2"><input name="addr2" type="text" id="addr2" class="text-input medium-input" maxlength="225" value="{data_addr2}" {disabled} /></dd>
    <div class="clear"></div>
    <dt>city</dt>
    <dd><input name="city" type="text" id="city" class="text-input medium-input" maxlength="125" value="{data_city}" {disabled} /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;">state</dt>
    <dd><select name="state" id="state"><option value="">-select-</option>{data_states}</select></dd>
    <div class="clear"></div>
    <dt>zip</dt>
    <dd>
    	<input name="zip" id="zc1" type="text" class="text-input small-input left" maxlength="5" value="{data_zc1}" {disabled} style="width: 80px;" />
    </dd>
    <div class="clear"></div>
</dl>

<hr class="dotted" />
<h2>add your social media connections and online presence:</h2>
<dl>
    <dt>website<!--<span class="required">*</span>--></dt>
    <dd><input type="text" maxlength="225" name="url" class="text-input medium-input" value="{data_url}" /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 0px;"><img src="{path_site}img/icon_blog.png" alt="youtube" width="25" height="25" /></dt>
    <dd><input type="text" maxlength="225" name="urlBlog" class="text-input medium-input" value="{data_blogurl}" /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;"><img src="{path_site}img/icon_twitter.png" alt="twitter" width="25" height="25" /></dt>
    <dd><input type="text" maxlength="225" name="urlTwtr" class="text-input medium-input" value="{data_twtrurl}" /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;"><img src="{path_site}img/icon_linkedin.png" alt="linkedin" width="25" height="25" /></dt>
    <dd><input type="text" maxlength="225" name="urlLi" class="text-input medium-input" value="{data_liurl}" /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;"><img src="{path_site}img/icon_facebook.png" alt="facebook" width="25" height="25" /></dt>
    <dd><input type="text" maxlength="225" name="urlFb" class="text-input medium-input" value="{data_fburl}" /></dd>
    <div class="clear"></div>
    <dt style="margin-top: 1px;"><img src="{path_site}img/icon_youtube.png" alt="youtube" width="25" height="25" /></dt>
    <dd><input type="text" maxlength="225" name="urlYt" class="text-input medium-input" value="{data_yturl}" /></dd>
    <div class="clear"></div>
</dl>
<hr class="dotted" />
<dl style="margin-bottom: 0;">
    <div class="clear"></div>
    <dt style="margin-top: 0;">date created</dt>
    <dd>{data_date}</dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;">date expires</dt>
    <dd>{data_exp}</dd>
    <div class="clear"></div>
</dl>
<button type="submit" name="submitupdate" class="sexy-button sexy-simple sexy-large sexy-pm-brown">Update</button>
<div class="clear"></div>
</form>
<hr class="dotted clear" />
<h2 style="margin-right: 12px; display: inline-block;">would you like to feature your account?</h2>
<input type="checkbox" name="featured" id="featured" value="1" {data_feat} style="margin-bottom: 12px;" /> Yes
<div id="featured-product-payment" class="yellow-box rounded-eight" style="display: none;">
	<form action="{path_site}profile/business.php" enctype="multipart/form-data" method="post" name="featured_business">
    <h2>featured account payment details</h2>
    <p>What is the vetting process for featuring my profile or a product?</p>
	<p>To feature either a product or your profile, you must submit an application to the Saturday Market Vendor Team.  Applications are processes and approved based on the following criteria:</p>
    <ul style="margin-left: 32px; margin-bottom: 12px;">
    	<li style="height: 16px; list-style: outside;">Your profile and products do not violate Saturday Market <a href="{path_site}terms/" target="_blank">Terms &amp; Conditions</a> or our Prohibited Items list and...</li>
        <li style="height: 16px; list-style: outside;">You do not have any outstanding or errant Vendor fulfillment reports</li>
        <li style="height: 16px; list-style: outside;">You have at least 5 Member referrals or...</li>
        <li style="height: 16px; list-style: outside;">You have a Member rating above 4.5 stars or...</li>
        <li style="height: 16px; list-style: outside;">You have not exceeded the number of weeks you can consecutively feature your profile or a specific product</li>
	</ul>
	<p>Your featured item will cost &#36;20.00 per week multiplied by the number of weeks you would like it to be featured.</p>

	<p>Additional questions? <a href="{path_site}contact/">Ask Us.</a></p>
    <hr class="dotted clear">
    <h3>billing address</h3>
    <dl>
        <dt>street</dt>
        <dd>
        	<input type="text" name="addr1" value="{data_addr1}" class="text-input medium-input" style="margin-bottom: 4px;" /><br />
            <input type="text" name="addr2" value="{data_addr2}" class="text-input medium-input" />
            <div class="clear" style="margin-bottom: 12px;"></div>
        </dd>
        <div class="clear"></div>
        <dt>city</dt>
        <dd><input type="text" name="city" value="{data_city}" class="text-input medium-input" /></dd>
        <dt style="margin-top: 0;">state</dt>
        <dd><select name="ccstate" id="ccstate"><option value="">-select-</option>{ccstates}</select></dd>
        <div class="clear"></div>
		<dt>zip</dt>
        <dd>
            <input name="zc1" id="zc1" class="text-input small-input left" maxlength="5" value="{data_zc1}" {disabled} style="width: 80px;" type="text">
            <span class="left" style="margin: 0pt 5px;"> - </span>
            <input name="zc2" id="zc2" class="text-input small-input left" maxlength="4" value="{data_zc2}" {disabled} style="width: 50px;" type="text">
        </dd>
        <div class="clear"></div>
   	</dl>
    <hr class="dotted clear">
    <h3>when would you like to be featured?</h3>
    <dl>
    	<dt style="margin-top: 0;">start date</dt>
        <dd>
        	<select name="start_date">
            	<option value="" class="state_select">-select-</option>{start_options}
            </select>
        </dd>
        <div class="clear"></div>
        <dt style="margin-top: 0;">weeks</dt>
        <dd>
        	<select name="num_weeks">
            	<option value="" class="num_weeks_select">-select-</option>{num_weeks}
            </select>
        </dd>
        <div class="clear"></div>	
        <dt style="margin-top: 0;">sub-total</dt>
        <dd>
        	&#36; <span id="txtsubtotal">{subtotal}</span>
            <input type="hidden" name="subtotal" value="{subtotal}" />
            <input type="hidden" name="fee" value="{fee}" />
        </dd>
        <div class="clear"></div>
        <dt style="margin-top: 0;">tax</dt>
        <dd>
        	&#36; <span id="txttax">{tax}</span><input type="hidden" name="tax" value="{tax}" />
        </dd>
        <div class="clear"></div>
        <dt style="margin-top: 0;">total</dt>
        <dd>
        	&#36; <span id="txttotal">{total}</span><input type="hidden" name="total" value="{total}" />
        </dd>
        <div class="clear"></div>
    </dl>
    <hr class="dotted clear">
    <h3>credit card information</h3>
    <div class="clear"></div>
    <dl>
        <dt>name on card</dt>
        <dd><input type="text" value="{data_ccName}" maxlength="150" name="ccName" class="text-input medium-input"></dd>
    	<div class="clear"></div>
        <dt style="margin-top: 0;">card type</dt>
        <dd>
        	<select name="ccType">
            	<option value="001">Visa</option>
            	<option value="002">MasterCard</option>
            </select>
        </dd>
    	<div class="clear"></div>
        <dt>card number</dt>
        <dd><input type="text" maxlength="20" name="ccNum" class="text-input medium-input"></dd>
    	<div class="clear"></div>
        <dt style="margin-top: 0;">card expiration</dt>
        <dd><select name="ccExpMM">{data_ccMM}</select>&nbsp;<select name="ccExpYY">{data_ccYY}</select></dd>
    	<div class="clear"></div>
        <dt>card cvv2 code</dt>
        <dd>
        	<div class="left"><input type="text" size="3" maxlength="4" name="ccSecCode" class="text-input large-input"></div>
            <a href="{path_site}modal_boxes/cvv2_code_help.php" onclick="$(this).modal({width: 425, height: 250}).open(); return false;"><img style="margin: 6px 0 0 12px;" class="left" title="card issuer phone number help" alt="card issuer phone number help" src="{path_site}img/icon_question_mark.png"></a>
	    	<div class="clear"></div>
        </dd>
	    <div class="clear"></div>
        <dt style="margin-top: 0;">card issuer phone</dt>
        <dd>
            <div class="left"><input type="text" value="{data_ccPhone}" maxlength="12" name="ccPhone" class="text-input large-input"></div>
            <a href="{path_site}modal_boxes/card_issuer_phone_help.php" onclick="$(this).modal({width: 475, height: 180}).open(); return false;"><img style="margin: 6px 0 0 6px;" class="left" title="card issuer phone number help" alt="card issuer phone number help" src="{path_site}img/icon_question_mark.png"></a>
	    	<div class="clear"></div>
        </dd>
	    <div class="clear"></div>
    </dl>
    <div class="clear"></div>
    <input type="hidden" name="type" value="business" />
	<button type="submit" name="submitFeat" class="sexy-button sexy-simple sexy-large sexy-pm-brown">submit feature application</button>
    </form>
</div>
<hr class="dotted clear" />