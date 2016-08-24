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
