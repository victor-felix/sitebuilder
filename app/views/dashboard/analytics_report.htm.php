<?php
    $traffic = $analytics->getTraffic();
    $totals = $traffic['totals'];
    $chartData = '[';
    $chartData .= '["Day", "Visits"]';
    foreach ($traffic['rows'] as $day => $visits) {
        $chartData .= ",[\"$day\", $visits]";
    }
    $chartData .= ']';
?>

<div id="chart_div" style="width: 800px; height: 450px;"></div>
<!-- traffic -->
<div class="traffic report-box" style="float:none; width:auto;">
    <ul>
        <li><b><?php echo number_format($totals['ga:visits']) ?></b> <?php echo s('Visits') ?></li>
        <li><b><?php echo number_format($totals['ga:pageviews']) ?></b> <?php echo s('Pageviews') ?></li>
    </ul>
    <ul>
        <li><b><?php echo  date('H:i:s', (int)$totals['ga:avgTimeOnSite']) ?></b> <?php echo s('Avg. Time on site') ?></li>
        <li><b><?php echo number_format($totals['ga:percentNewVisits'], 2) ?></b> <?php echo s('%% New Visits') ?></li>
    </ul>
</div>
<!-- traffic -->
<!-- top pages -->
<div class="report-box" style="float:none; width:auto; clear: both;">
    <h3>Top pages</h3>
    <ul>
        <?php foreach ($analytics->getTopPages(5) as $row): ?>
        <li><?php echo s('%s - <i>%u visits</i>',$row[0],$row[1]) ?></li>
        <?php endforeach;?>
    </ul>
</div>
<!-- top pages -->

<!-- Mobile report -->
<?php $mobileTraffic = $analytics->getMobileTraffic() ?>
<div class="report-box">
    <h3>Top Mobile Systems</h3>
    <ul>
        <?php 
        foreach ($mobileTraffic['system'] as $system => $visits):?>
        <li><?php echo s('%s - <i>%u visits</i>, %% %s',$system,$visits, number_format(($visits * 100) / $mobileTraffic['total'], 2) ) ?></li>
        <?php endforeach;?>
    </ul>
</div>
<div class="report-box">
    <h3>Top Screen sizes</h3>
    <ul>
        <?php 
        $i = 0;
        foreach ($mobileTraffic['screen'] as $screem => $visits):
        ?>
        <li><?php echo s('%s - <i>%u visits</i>, %% %s',$screem,$visits, number_format(($visits * 100) / $mobileTraffic['total'], 2)) ?></li>
        <?php
        if (++$i > 9) {
            break;
        }
        endforeach;
        ?>
    </ul>
</div>
<!-- Mobile report -->
<script type="text/javascript">
    var lineChartData = <?php echo $chartData; ?>;
</script>