{returnmsg}
<form id="register-business" action="{path_site}profile/join.php?action=vendor" method="post" name="register">
<h2 class="divider">Login information</h2>
<dl>
    <dt>username</dt>
    <dd>
        <input name="user_name" type="text" class="text-input medium-input left" maxlength="50" value="{data_un}" />
        <a href="{path_site}modal_boxes/username_help.php" onclick="$(this).modal({width: 375, height: 180}).open(); return false;"><img style="margin: 6px 0 0 6px;" class="left" title="username help" alt="username help" src="{path_site}img/icon_question_mark.png"></a>
        <div class="clear"></div>
    </dd>
    <div class="clear"></div>
    <dt>email</dt>
    <dd><input name="user_email" type="text" class="text-input medium-input" maxlength="235" value="{data_em}" /></dd>
    <div class="clear"></div>
    <dt>password</dt>
    <dd><input name="user_pass" type="password" class="text-input medium-input" maxlength="30" /></dd>
    <div class="clear"></div>
    <dt>verify password</dt>
    <dd><input name="user_pass2" type="password" class="text-input medium-input" maxlength="30" /></dd>
    <div class="clear"></div>
</dl>
<br />
<h2 class="divider">Company information</h2>
<h3>NOTE: The address below must be on file with the credit card information entered below.</h3>
<dl>
    <dt>company name</dt>
    <dd><input name="name" type="text" class="text-input medium-input required" maxlength="125" value="{data_name}" /></dd>
    <div class="clear"></div>
    <dt>street address</dt>
    <dd><input name="addr1" type="text" id="addr1" class="text-input medium-input" maxlength="225" value="{data_addr1}" {disabled} /></dd>
    <div class="clear"></div>
    <dt></dt>
    <dd><input name="addr2" type="text" id="addr2" class="text-input medium-input" maxlength="225" value="{data_addr2}" {disabled} /></dd>
    <div class="clear"></div>
    <dt>city</dt>
    <dd><input name="city" type="text" id="city" class="text-input medium-input" maxlength="125" value="{data_city}" {disabled} /></dd>
    <div class="clear"></div>
    <dt>state</dt>
    <dd style="padding-top: 5px;"><select name="state" id="state"><option value="">-select-</option>{data_states}</select></dd>
    <div class="clear"></div>
    <dt>zip</dt>
    <dd>
        <input name="zc1" id="zc1" type="text" class="text-input small-input left" maxlength="5" value="{data_zc1}" {disabled} style="width: 80px;" />
        <span class="left" style="margin: 0 5px;"> - </span>
        <input name="zc2" id="zc2" type="text" class="text-input small-input left" maxlength="4" value="{data_zc2}" {disabled} style="width: 50px;" />
        <div class="clear"></div>
    </dd>
    <div class="clear"></div>
</dl>
<br />
<h2 class="divider">Payment information</h2>
<h3>Posting a business profile on Saturday Market is only &#36;20 a month.</h3>
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
</dl>

<p><input type="checkbox" name="agree" value="1"{data_agree} />&nbsp;&nbsp;I am a small business owner and agree to the Saturday Market <a href="{path_site}terms/">Terms &amp; Conditions</a> and
<a href="{path_site}privacy/" target="_blank">Privacy Policy</a>. I acknowledge and agree that any products provided for sale on PM will be adjusted 3.5&#37; of the posted sale price.</p>
<input type="hidden" id="grand-total" name="total" value="20.00" />
<br />
<hr class="dotted" />
<p>You will be sent an email to verify your new {site_title} account. Upon verification you will be provided further instructions on setting up your account.</p>
<div class="clear"></div>
<div class="yellow-box rounded-eight">
    Total to be processed for annual registration:<strong> &#36;<span id="totalamt">{data_promo}</span></strong><br />    
    <span id="promomsg"></span>
    <div style="margin-left:50px;margin-bottom:-12px;">Promo Code: <input name="promocode" id="promocode" type="text" /> <button id="submit_promo" type="button" name="submit_promo" class="sexy-button sexy-simple sexy-small sexy-pm-brown" style="margin:8px 0 12px 6px;">apply code</button></div>
</div>
<div class="clear"></div>
<div>
    <button type="submit" name="submit_conf" class="sexy-button sexy-simple sexy-large sexy-pm-brown">submit registration</button>
</div>
<div class="clear"></div>
</form>
