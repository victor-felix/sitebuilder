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
