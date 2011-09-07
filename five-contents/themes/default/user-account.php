<?php show_view('header-w-wrap'); ?>

<?php show_view('sidebar-profile-left')?>

<div class="profile-content">

<form id="mainform" name="mainform" action="<?php echo Router::url(array('controller' => 'user', 'action' => 'account')); ?>" method="post">
<h2>login information</h2>
<dl>
	<dt>user name</dt>
    <dd>
    	<input name="username" type="text" class="text-input medium-input" maxlength="50" value="<?php echo $user->get('username'); ?>">
       	<br><small>between 6 - 30 characters</small>
    </dd>
    <div class="clear"></div>
    <dt>email address</dt>
    <dd>
        <input name="email" type="text" class="text-input medium-input" maxlength="250" value="<?php echo $user->get('email'); ?>">
        <br><small>example: youremail@yourdomain.com</small>
    </dd>
    <div class="clear"></div>
    <dt>reset password</dt>
    <dd>
        <input name="password" type="password" class="text-input medium-input" maxlength="30">
        <br><small>between 6 - 30 characters</small>
    </dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;">confirm password</dt>
    <dd>
        <input name="_xpassword" type="password" class="text-input medium-input" maxlength="30">
        <br><small>between 6 - 30 characters</small>
    </dd>
	<div class="clear"></div>
</dl>
<hr class="dotted clear">
<h2>personal information</h2>
<p>This information is for shipping and billing purposes and must match the corresponding information on file with your method of payment
used. If this address is not correct, your order may be delayed or canceled.</p>
<dl>
	<dt>first name</dt>
	<dd><input name="firstName" type="text" class="text-input" maxlength="100" value="<?php echo $user->get('firstName'); ?>"></dd>
    <div class="clear"></div>
    <dt>middle initial</dt>
    <dd><input size="2" name="mi" type="text" class="text-input" maxlength="2" value="<?php echo $user->get('mi'); ?>"></dd>
    <div class="clear"></div>
    <dt>last name</dt>
    <dd><input name="lastName" type="text" class="text-input" maxlength="100" value="<?php echo $user->get('lastName'); ?>"></dd>
    <div class="clear"></div>
    <dt>address</dt>
    <dd>
        <input name="addr1" value="<?php echo $user->get('addr1'); ?>" type="text" class="text-input medium-input" maxlength="250" style="margin-bottom: 5px;">
    </dd>
    <dd class="addr2">
        <input name="addr2" value="<?php echo $user->get('addr2'); ?>" type="text" class="text-input medium-input" maxlength="250">
    </dd>
    <div class="clear"></div>
    <dt>city</dt>
    <dd><input name="city" value="<?php echo $user->get('city'); ?>" type="text" class="text-input" maxlength="100"></dd>
    <div class="clear"></div>
    <dt>state</dt>
    <dd>
        <select class="input01" name="state">
            <option value="0">-- SELECT --</option>
            <?php 
            $states = array(
            'AL'	=> 'Alabama (AL)',
            'AK'	=> 'Alaska (AK)',
            'AZ'	=> 'Arizona (AZ)',
            'AR'	=> 'Arkansas (AR)',
            'CA'	=> 'California (CA)',
            'CO'	=> 'Colorado (CO)',
            'CT'	=> 'Conneticut (CT)',
            'DC'	=> 'District of Columbia (DC)',
            'DE'	=> 'Delaware (DE)',
            'FL'	=> 'Florida (FL)',
            'GA'	=> 'Georgia (GA)',
            'HI'	=> 'Hawaii (HI)',
            'ID'	=> 'Idaho (ID)',
            'IL'	=> 'Illinois (IL)',
            'IN'	=> 'Indiana (IN)',
            'IA'	=> 'Iowa (IA)',
            'KS'	=> 'Kansas (KS)',
            'KY'	=> 'Kentucky (KY)',
            'LA'	=> 'Louisiana (LA)',
            'ME'	=> 'Maine (ME)',
            'MD'	=> 'Maryland (MD)',
            'MA'	=> 'Massachusetts (MA)',
            'MI'	=> 'Michigan (MI)',
            'MN'	=> 'Minnesota (MN)',
            'MS'	=> 'Mississippi (MS)',
            'MO'	=> 'Missouri (MO)',
            'MT'	=> 'Montana (MT)',
            'NE'	=> 'Nebraska (NE)',
            'NV'	=> 'Nevada (NV)',
            'NH'	=> 'New Hampshire (NH)',
            'NJ'	=> 'New Jersey (NJ)',
            'NM'	=> 'New Mexico (NM)',
            'NY'	=> 'New York (NY)',
            'NC'	=> 'North Carolina(NC)',
            'ND'	=> 'North Dakota (ND)',
            'OH'	=> 'Ohio (OH)',
            'OK'	=> 'Oklahoma (OK)',
            'OR'	=> 'Oregon (OR)',
            'PA'	=> 'Pennsylvania (PA)',
            'PR'	=> 'Puerto Rico (PR)',
            'RI'	=> 'Rhode Island (RI)',
            'SC'	=> 'South Carolina (SC)',
            'SD'	=> 'South Dakota',
            'TN'	=> 'Tennessee (TN)',
            'TX'	=> 'Texas (TX)',
            'UT'	=> 'Utah (UT)',
            'VA'	=> 'Virginia (VA)',
            'VI'	=> 'Virgin Islands (VI)',
            'VT'	=> 'Vermont (VT)',
            'WA'	=> 'Washington (WA)',
            'WV'	=> 'West Virginia (WV)',
            'WI'	=> 'Wisconsin (WI)',
            'WY'	=> 'Wyoming (WY)',
            );
            
			foreach ((array)$states as $abbr => $state): ?>
            	<option value="<?php echo $abbr; ?>" <?php echo ($user->get('state') == $abbr)?'selected="selected"':''; ?>><?php echo $state; ?></option>
            <?php endforeach; ?>
            
		</select>
    </dd>
    <div class="clear"></div>
    <dt>zip code</dt>
    <dd>
    	<div>
            <input name="zip" value="<?php echo $user->get('zip'); ?>" type="text" class="text-input left" maxlength="5" size="4">
            <div class="clear"></div>
        </div>
    </dd>
    <div class="clear"></div>
    <dt style="margin-top: 0;">main phone number</dt>
    <dd>
    	<div>
            <input name="mainPhone" value="<?php echo $user->get('mainPhone'); ?>" type="text" class="text-input" style="margin-right: 5px;" maxlength="10" size="7">
            <br><small>the main phone number must be on file with the credit card used for any purchases made. please do not enter dashes.</small>
		    <div class="clear"></div>
        </div>
    </dd>
    <div class="clear"></div>
   	<dt style="margin-top: 0;">alt phone number</dt>
    <dd>
        <input name="altPhone" value="<?php echo $user->get('altPhone'); ?>" type="text" class="text-input" style="margin-right: 5px;" maxlength="10" size="7">
        <br><small>this usually is a cell phone number. if a cell phone number is used some features for support, messaging, live chat, etc. can be done anywhere on your mobile device.</small>
    </dd>
    <div class="clear"></div>
</dl>
<div class="clear"></div>
<button type="submit" name="submitupdate" id="submitupdate" class="sexy-button sexy-simple sexy-large sexy-pm-brown">update profile</button>
</form>

            <div class="clear"></div>
        </div>

<?php show_view('footer-w-wrap'); ?>