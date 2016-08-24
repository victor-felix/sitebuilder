<?php $this->pageTitle = 'Status' ?>
<table class="table">
	<thead>
		<tr>
			<th>Worker</th>
			<th>Prio</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($workerStatuses as $status): ?>
			<tr class="worker-status-<?= $status['ok'] ? 'ok' : 'nok' ?>">
				<td><?= $status['worker'] ?></td>
				<td><?= $status['priority'] ? 'High' : 'Low' ?></td>
				<td><?= $status['ok'] ? 'OK' : 'NOK' ?></td>
			</tr>
		<?php endforeach ?>
		<tr class="worker-status-<?= $oldestJobStatus['ok'] ? 'ok' : 'nok' ?>">
			<td colspan="2"><?= $oldestJobStatus['worker'] ?></td>
			<td><?= $oldestJobStatus['ok'] ? 'OK' : 'NOK' ?></td>
		</tr>
	</tbody>
</table>

<table class="table">
	<thead>
		<tr>
			<th>Site</th>
			<th>Status</th>
			<th>Content-Type</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($apiEndpointStatuses as $status): ?>
			<tr>
				<td><?= $status['site'] ?></td>
				<td><?= $status['status'] ?></td>
				<td><?= $status['content_type'] ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
