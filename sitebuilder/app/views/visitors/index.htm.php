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
			<?php
			$versionsJson = '';
			foreach ($report['appVersions'] as $version => $total)
				$versionsJson .= "{value: {$total}, label: '$version'},";

			$this->html->scriptsForLayout .= "
				<script>
				Morris.Donut({
					element: 'subscribed-graph',
					data: [
						{value: {$report['subscribedPercent']}, label: '" . s('Subscribed') . "'},
						{value: {$report['unsubscribedPercent']}, label: '" . s('Unsubscribed') . "'},
					],
					formatter: function (x) { return x + '%'}
				});
				Morris.Donut({
					element: 'accepted-graph',
					data: [
						{value: {$report['accepted']}, label: '" . s('Accepted') . "'},
						{value: {$report['invited']}, label: '" . s('Invited') . "'},
					]
				});
				Morris.Donut({
					element: 'versions-graph',
					data: [$versionsJson]
				});
				</script>";
			?>
		</div>
		<?php endif ?>
		<table id="visitors-list" class="display" cellspacing="0" width="100%">
				<thead>
						<tr>
								<th><?= s('Email') ?></th>
								<th><?= s('Groups') ?></th>
								<th><?= s('Last Login') ?></th>
						</tr>
				</thead>
				<tbody>
						<?php foreach($visitors as $visitor): ?>
						<tr>
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
	</div>
	<div class="clear"></div>
</div>
