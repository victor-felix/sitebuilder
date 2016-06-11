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
	</tbody>
</table>
