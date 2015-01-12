<?php
class ChartHelper extends Helper {
	public function pie($elementId, $data) {
		//TODO use array map
		$graphData = [];
		foreach($data as $label => $value) {
			$chartData[] = ['label' => $label, 'value' => $value];
		}
		return "
			<script>
			Morris.Donut({
				element: '$elementId',
				data: " . json_encode($graphData) ."
			});
			</script>";
	}
}
