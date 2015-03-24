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
		<table id="visitors-list" class="display list" cellspacing="0" width="100%">
				<thead>
						<tr>
								<th><?= s('Email') ?></th>
								<th><?= s('First Name') ?></th>
								<th><?= s('Last Name') ?></th>
								<th><?= s('Groups') ?></th>
								<th><?= s('Last Login') ?></th>
						</tr>
				</thead>
				<tbody>
						<?php foreach($visitors as $visitor): ?>
						<tr onclick="document.location = '/visitors/edit/<?= $visitor->id(); ?>'" class="clickable">
							<td><?= $visitor->email() ?></td>
							<td><?= $visitor->firstName() ?></td>
							<td><?= $visitor->lastName() ?></td>
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
				<?= $this->html->link(s('Add Visitor'), '/visitors/add', [
					'class' => 'ui-button large add push-scene',
					'style' => 'margin-bottom: 40px'
				]) ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<script>
	window.visitorGraphData = <?= $visitorGraphDataJson ?>;
</script>
