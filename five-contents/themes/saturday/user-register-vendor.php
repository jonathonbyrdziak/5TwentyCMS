<?php show_view('header-w-wrap'); ?>
<?php show_view('sidebar-profile-left')?>

<div class="profile-content">

	<h1>vendor registration</h1>
	
	{returnmsg}
	<form id="register-business" action="<?php echo Router::url(array('controller'=>'user','action'=>'register','vendor')); ?>" method="post" name="register">
	<input type="hidden" name="id" value="<?php echo $business->get('id'); ?>" />
	<input type="hidden" name="userid" value="<?php echo get_current_user_id(); ?>" />
	
	<h2 class="divider">Company information</h2>
	<h3>NOTE: The address below must be on file with the credit card information entered below.</h3>
	<dl>
	    <dt>company name</dt>
	    <dd><input name="name" type="text" class="text-input medium-input required" maxlength="125" value="<?php echo $business->get('name'); ?>" /></dd>
	    <div class="clear"></div>
	    <dt>street address</dt>
	    <dd><input name="addr1" type="text" id="addr1" class="text-input medium-input" maxlength="225" value="<?php echo $business->get('addr1'); ?>" /></dd>
	    <div class="clear"></div>
	    <dt></dt>
	    <dd><input name="addr2" type="text" id="addr2" class="text-input medium-input" maxlength="225" value="<?php echo $business->get('addr2'); ?>" /></dd>
	    <div class="clear"></div>
	    <dt>city</dt>
	    <dd><input name="city" type="text" id="city" class="text-input medium-input" maxlength="125" value="<?php echo $business->get('city'); ?>" /></dd>
	    <div class="clear"></div>
	    <dt>state</dt>
	    <dd style="padding-top: 5px;"><select name="state" id="state">
            <option value="0">-- SELECT --</option>
            <option value="AL">Alabama (AL)</option><option value="AK">Alaska (AK)</option><option value="AZ">Arizona (AZ)</option><option value="AR">Arkansas (AR)</option><option value="CA">California (CA)</option><option value="CO">Colorado (CO)</option><option value="CT">Conneticut (CT)</option><option value="DC">District of Columbia (DC)</option><option value="DE">Delaware (DE)</option><option value="FL">Florida (FL)</option><option value="GA">Georgia (GA)</option><option value="HI">Hawaii (HI)</option><option value="ID">Idaho (ID)</option><option value="IL">Illinois (IL)</option><option value="IN">Indiana (IN)</option><option value="IA">Iowa (IA)</option><option value="KS">Kansas (KS)</option><option value="KY">Kentucky (KY)</option><option value="LA">Louisiana (LA)</option><option value="ME">Maine (ME)</option><option value="MD">Maryland (MD)</option><option value="MA">Massachusetts (MA)</option><option value="MI">Michigan (MI)</option><option value="MN">Minnesota (MN)</option><option value="MS">Mississippi (MS)</option><option value="MO">Missouri (MO)</option><option value="MT">Montana (MT)</option><option value="NE">Nebraska (NE)</option><option value="NV">Nevada (NV)</option><option value="NH">New Hampshire (NH)</option><option value="NJ">New Jersey (NJ)</option><option value="NM">New Mexico (NM)</option><option value="NY">New York (NY)</option><option value="NC">North Carolina(NC)</option><option value="ND">North Dakota (ND)</option><option value="OH">Ohio (OH)</option><option value="OK">Oklahoma (OK)</option><option value="OR">Oregon (OR)</option><option value="PA">Pennsylvania (PA)</option><option value="PR">Puerto Rico (PR)</option><option value="RI">Rhode Island (RI)</option><option value="SC">South Carolina (SC)</option><option value="SD">South Dakota</option><option value="TN">Tennessee (TN)</option><option value="TX">Texas (TX)</option><option value="UT">Utah (UT)</option><option value="VA">Virginia (VA)</option><option value="VI">Virgin Islands (VI)</option><option value="VT">Vermont (VT)</option><option value="WA" selected="selected">Washington (WA)</option><option value="WV">West Virginia (WV)</option><option value="WI">Wisconsin (WI)</option><option value="WY">Wyoming (WY)</option>
	    </select></dd>
	    <div class="clear"></div>
	    <dt>zip</dt>
	    <dd>
	        <input name="zip" id="zip" type="text" class="text-input small-input left" maxlength="5" value="<?php echo $business->get('zip'); ?>" style="width: 80px;" />
	        <div class="clear"></div>
	    </dd>
	    <div class="clear"></div>
	</dl>
	<br />
	
	<h2 class="divider">Payment information</h2>
	<h3>Posting a business profile on Saturday Market is only &#36;20 a year.</h3>
	<div class="clear"></div>
	<dl>
	    <dt>name on card</dt>
	    <dd><input type="text" value="<?php echo BRequest::getVar('_ccName'); ?>" maxlength="150" name="_ccName" class="text-input medium-input"></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0;">card type</dt>
	    <dd>
	        <select name="_ccType">
	            <option value="visa">Visa</option>
	            <option value="mastercard">MasterCard</option>
	        </select>
	    </dd>
	    <div class="clear"></div>
	    
	    <dt>card number</dt>
	    <dd><input type="text" maxlength="20" name="_ccNum" class="text-input medium-input" value="<?php echo BRequest::getVar('_ccNum'); ?>" /></dd>
	    <div class="clear"></div>
	    
	    <dt style="margin-top: 0;">card expiration</dt>
	    <dd>
		    <select name="_ccExpMM">
		    	<option>01</option>
		    	<option>02</option>
		    	<option>03</option>
		    	<option>04</option>
		    	<option>05</option>
		    	<option>06</option>
		    	<option>07</option>
		    	<option>08</option>
		    	<option>09</option>
		    	<option>10</option>
		    	<option>11</option>
		    	<option>12</option>
		    </select>&nbsp;
		    <select name="_ccExpYY">
		    	<option>11</option>
		    	<option>12</option>
		    	<option>13</option>
		    	<option>14</option>
		    	<option>15</option>
		    	<option>16</option>
		    	<option>17</option>
		    	<option>18</option>
		    	<option>19</option>
		    	<option>20</option>
		    </select>
	    </dd>
	    <div class="clear"></div>
	    
	    <dt>card cvv2 code</dt>
	    <dd>
	        <div class="left">
		        <input type="text" size="3" maxlength="4" name="_ccSecCode" class="text-input large-input" value="<?php echo BRequest::getVar('_ccSecCode'); ?>" />
	        </div>
	        <a href="{path_site}modal_boxes/cvv2_code_help.php" onclick="$(this).modal({width: 425, height: 250}).open(); return false;"><img style="margin: 6px 0 0 12px;" class="left" title="card issuer phone number help" alt="card issuer phone number help" src="{path_site}img/icon_question_mark.png"></a>
	        <div class="clear"></div>
	    </dd>
	    <div class="clear"></div>
	</dl>
	
	<p><input type="checkbox" name="agree" value="1" />&nbsp;&nbsp;I am a small business owner and agree to the Saturday Market <a href="{path_site}terms/">Terms &amp; Conditions</a> and
	<a href="{path_site}privacy/" target="_blank">Privacy Policy</a>. I acknowledge and agree that any products provided for sale on PM will be adjusted 3.5&#37; of the posted sale price.</p>
	<input type="hidden" id="grand-total" name="total" value="20.00" />
	<br />
	<hr class="dotted" />
	<p>You will be sent an email to verify your new {site_title} account. Upon verification you will be provided further instructions on setting up your account.</p>
	<div class="clear"></div>
	
	<div class="yellow-box rounded-eight">
	    Total to be processed for annual registration:<strong> &#36;<span id="totalamt">20.00</span></strong><br />    
	    <span id="promomsg"></span>
	    <div style="margin-left:50px;margin-bottom:-12px;">
		    Promo Code: <input name="_promocode" id="promocode" type="text" /> 
	    </div>
	    <br/>
	</div>
	
	<div class="clear"></div>
	<div>
	    <button type="submit" name="submit_conf" class="sexy-button sexy-simple sexy-large sexy-pm-brown">submit registration</button>
	</div>
	<div class="clear"></div>
	</form>
		
	
</div>

<?php show_view('footer-w-wrap'); ?>