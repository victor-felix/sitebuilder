<?php 
	$currentSite = Auth::user()->site();
	$this->pageTitle = s('Dashboard');
?>
<div class="dashboard">
	<div class="wrapp">
		<div class="tip-big">
			<h2><?php echo s('welcome to your mobile site')?></h2>
			<p><?php echo s('use the tools below to keep improving your mobile site') ?></p>
		</div>
		<ul class="list">
			<li>
				<a href="dashboard/photos">
					<h3><?php echo s('add photos of your company')?></h3>
					<small><?php echo s('let your customers see what your business looks like')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li>
				<a href="dashboard/social">
					<h3><?php echo s('your social links')?></h3>
					<small><?php echo s('facebook page, twitter, website address')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li>
				<a href="dashboard/address">
					<h3><?php echo s('your address')?></h3>
					<small><?php echo s('full address adds a map on your mobile site')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li>
				<a href="dashboard/contact">
					<h3><?php echo s('your contacts')?></h3>
					<small><?php echo s('phone numbers and email address')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li>
				<a href="dashboard/feed">
					<h3><?php echo s('add a news feed')?></h3>
					<small><?php echo s('use your website RSS to display news')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li>
				<a href="categories/">
					<h3><?php echo s('manage other content')?></h3>
					<small><?php echo s('you can add other content such as products and events')?></small>
					<span class="arrow"></span>
				</a>
			</li>
		</ul>
		<div class="domain">
			<p><?php echo s('you can access anytime from your mobile phone')?></p>
			<?php echo $this->html->link('http://' . e($currentSite->domain), 'http://' . e($currentSite->domain), array('target'=>"blank")) ?>
		</div>
	</div>
	<?php echo $this->element('sites/theme_preview', array(
		'site' => $currentSite,
		'autoload' => true
	)) ?>
</div>