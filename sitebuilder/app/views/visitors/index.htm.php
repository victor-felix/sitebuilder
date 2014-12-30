<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Visitors') ?></h1>
	</div>
	<div class="clear"></div>
</div>
<div>
	<div class="grid-4 first">
		<div class="tip">
			<h4><?php echo s('Tip') ?></h4>
			<p><?php echo s('Use panel on right of screen to manage your visitors.') ?></p>
		</div>
	</div>
	<div class="grid-8">
		<div>
			<?php print_r($report); ?>
			<div id="subscribed-graph"></div>
			<div id="accepted-graph"></div>
			<div id="versions-graph"></div>
			<?php
			$versionsJson = '';
			foreach ($report['appVersions'] as $version => $total)
				$versionsJson .= "{value: {$total}, label: '$version'},";

			$this->html->scriptsForLayout .= "
				<script>
				Morris.Donut({
					element: 'subscribed-graph',
					data: [
						{value: {$report['subscribed']}, label: 'Subscribed'},
						{value: {$report['unsubscribed']}, label: 'Unsubscribed'},
					]
				});
				Morris.Donut({
					element: 'accepted-graph',
					data: [
						{value: {$report['accepted']}, label: 'Accepted'},
						{value: {$report['invited']}, label: 'Invited'},
					]
				});
				Morris.Donut({
					element: 'versions-graph',
					data: [$versionsJson]
				});
				</script>";
			?>
		</div>
		<table id="visitors-list" class="display" cellspacing="0" width="100%">
				<thead>
						<tr>
								<th>Email</th>
								<th>Groups</th>
								<th>Last Login</th>
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
