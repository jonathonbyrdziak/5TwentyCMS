<?php $user = FiveFactory::getUser(); ?>
<div class="sidebar_profile_left" id="leftCol">
	<div id="pagelet_profile_picture">
		<div class="profile-picture">
			<span class="profile-picture-overlay"></span>
			<a class="edit_profilepicture" href="<?php echo Router::url(array('controller' => 'user', 'action' => 'social')); ?>">
				Change Picture<span class="edit_profilepicture_icon"></span></a>
			<?php avatar(); ?>
		</div>
	</div>


<div id="pagelet_fbx_navigation" data-referrer="pagelet_fbx_navigation">
<div id="sideNav" class="mvm profileSelf">
<div class="expandableSideNav expandedMode">
<div>
<ul class="uiSideNav" role="navigation">
	<li class="sideNavItem selectedItem">
		<a class="item" 
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'profile')); ?>">
		<span class="linkWrap">Dashboard</span> 
		</a><span class="loadingIndicator"></span></li>
		
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'orders')); ?>"><span
		class="linkWrap">Order History</span> </a><span
		class="loadingIndicator"></span></li>
		
	<li class="sideNavItem selectedItem">
		<a class="item" href="<?php echo Router::url(array('controller' => 'user', 'action' => 'account')); ?>">
		<span class="linkWrap">Account Information</span> 
		</a><span class="loadingIndicator"></span></li>
	
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'social')); ?>"><span
		class="linkWrap">Social Information</span> </a><span
		class="loadingIndicator"></span></li>
		
	<?php if ($user->is_business()): ?>
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'business' )); ?>"><span
		class="linkWrap">Business Information</span> </a><span
		class="loadingIndicator"></span></li>
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'public', get_publicid(get_current_user_id()) )); ?>"><span
		class="linkWrap">View Public Profile</span> </a><span
		class="loadingIndicator"></span></li>
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'service' )); ?>"><span
		class="linkWrap">Service Information</span> </a><span
		class="loadingIndicator"></span></li>
	<li class="sideNavItem key-info" id="navItem_info"><a class="item"
		href="<?php echo Router::url(array('controller' => 'user', 'action' => 'products' )); ?>"><span
		class="linkWrap">Product Management</span> </a><span
		class="loadingIndicator"></span></li>
	<?php endif; ?>
</ul>
</div>
</div>
</div>
</div>
</div>