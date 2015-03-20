<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = s('Visitors') ?></h1>
	</div>
	<div class="clear"></div>
</div>
<div>
	<div class="grid-12">
		<div class="graph-wrapper">
			<?php if($report['totalVisitors']): ?>
			<div class="graph">
				<h2><?= s('Push Subscription') ?></h2>
				<div id="subscribed-graph"></div>
			</div>
			<div class="graph">
				<h2><?= s('Invitations') ?></h2>
				<div id="accepted-graph"></div>
			</div>
			<div class="graph">
				<h2><?= s('App Versions') ?></h2>
				<div id="versions-graph"></div>
			</div>
		</div>
		<?php endif ?>
		<table id="visitors-list" class="display bulk-action-list" cellspacing="0" width="100%">
				<thead>
						<tr>
								<th class="no-sort"></th>
								<th><?= s('Email') ?></th>
								<th><?= s('Groups') ?></th>
								<th><?= s('Last Login') ?></th>
						</tr>
				</thead>
				<tbody>
						<?php foreach($visitors as $visitor): ?>
						<tr>
								<td><input class="select-row" type="checkbox" name="visitors[]" value="<?= $visitor->id() ?>"></td>
								<td><?= $visitor->email() ?></td>
								<td>
									<?php foreach($visitor->groups() as $group): ?>
										<span class="badge"><?= $group ?></span>
									<?php endforeach ?>
								</td>
								<td><?= $visitor->lastLogin() ?></td>
						</tr>
						<?php endforeach; ?>
				</tbody>
		</table>
		<div class="fieldset-actions">
			<div class="grid-4 first">
				<?php echo $this->html->link(s('Add Visitor'), '/visitors/add', array(
					'class' => 'ui-button large add push-scene',
					'style' => 'margin-bottom: 40px'
				)) ?>
			</div>
			<div class="grid-8 bulk-actions hidden">
				<?php echo $this->html->link(s('Reset Password'), '/visitors/remove', array( 'class' => 'ui-button' )) ?>
				<?php echo $this->html->link(
					$this->html->image('shared/categories/delete.gif') . s('Delete item'),
					'/business_items/delete/', array(
						'class' => 'ui-button has-confirm',
						'data-confirm' => '#delete-confirm'
					)
				) ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<script>
	window.visitorGraphData = <?= $visitorGraphDataJson ?>;
</script>
