<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Customize') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create(null, array(
	'id' => 'form-edit-customize',
	'class' => 'form-edit default-form',
	'method' => 'file',
	'object' => $site
)) ?>

	<fieldset style="position: relative;">
		<div class="themes">
			<div class="tip-big">
				<h2><?php echo s('customize your theme') ?></h2>
			</div>
			<div class="customize-theme">
				<ul class="featured-list">
					<li class="open">
						<div class="link">
							<span class="icon"></span>
							<h3><?php echo s('appearance') ?></h3>
							<small><?php echo s('you can add a restaurant menu, products, services, etc') ?></small>
							<span class="arrow open"></span>
						</div>
						<div class="content">
							<p class="title">Paraty</p>
	
							<ul class="skin-picker">
								<li class="" data-skin="517e6d01198b6623ad000031">
									<span style="background-color: #405f9c"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000032">
									<span style="background-color: #c66161"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000033">
									<span style="background-color: #86d386"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000034">
									<span style="background-color: #eded89"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000035">
									<span style="background-color: #dfa1df"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000036">
									<span style="background-color: #a0e1e1"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000037">
									<span style="background-color: #0392CE"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000038">
									<span style="background-color: #B61B1B"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad000039">
									<span style="background-color: #66B032"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad00003a">
									<span style="background-color: #FB9902"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad00003b">
									<span style="background-color: #8601AF"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad00003c">
									<span style="background-color: #8080c8"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad00003d">
									<span style="background-color: #3d3939"></span>
								</li>
								<li class="" data-skin="517e6d01198b6623ad00003e">
									<span style="background-color: #a9a9a9"></span>
								</li>
							</ul>
							
							<ul class="color-picker">
								<li>
									<span>color #01</span>
									<span class="color" data-color="#8080c8" style="background-color: #8080c8"></span>
								</li>
								<li>
									<span>color #02</span>
									<span class="color" data-color="#0392CE" style="background-color: #0392CE"></span>
								</li>
								<li>
									<span>color #03</span>
									<span class="color" data-color="#a9a9a9" style="background-color: #a9a9a9"></span>
								</li>
								<li>
									<span>color #04</span>
									<span class="color" data-color="#66B032" style="background-color: #66B032"></span>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<?php echo $this->element('sites/theme_preview', array(
			'site' => $site,
			'autoload' => true
		)) ?>
	</fieldset>


	<fieldset class="actions">
		<?php echo $this->form->submit(s('Save and Continue'), array(
			'class' => 'ui-button red larger save-continue'
		)) ?>
		<?php echo $this->form->submit(s('Save'), array(
			'class' => 'ui-button red larger save'
		)) ?>
	</fieldset>

<?php echo $this->form->close() ?>
