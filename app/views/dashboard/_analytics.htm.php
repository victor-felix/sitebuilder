<div class="analytics" >
<?php if (!$analytics) : ?>
    <p>Analytics not is enabled</p>
<?php else: ?>
    <?php if ($analytics->isAuthenticated()): ?>
        <?php if ($analytics->profile_id): ?>
            <h2 class="title"><?php echo s('Visits over past 30 days')?></h2>
            <!-- traffic -->
            <div class="traffic report-box" style="float:none; width:auto;">
            <?php 
                $traffic = $analytics->getTraffic();
                $totals = $traffic['totalsForAllResults'];
            ?>
                <ul>
                    <li><b><?php echo number_format($totals['ga:visits']); ?></b> <?php echo s('Visits') ?></li>
                    <li><b><?php echo number_format($totals['ga:pageviews']); ?></b> <?php echo s('Pageviews') ?></li>
                </ul>
                <ul>
                    <li><b><?php echo  date('H:i:s', (int)$totals['ga:avgTimeOnSite']); ?></b> <?php echo s('Avg. Time on site') ?></li>
                    <li><b><?php echo number_format($totals['ga:percentNewVisits'], 2); ?></b> <?php echo s('%% New Visits') ?></li>
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
            
        <?php else: ?>
        <!-- Select profile form -->
            <form action="/dashboard/profile"  method="POST" >
            <p>Select analytics profile</p>
            <select name="profile" >
            <?php foreach($analytics->getProfiles() as $profile): ?>
                <option value="<?php echo $profile['id'], ',', $profile['webPropertyId'] ?>"><?php echo $profile['name'] ?></option>
            <?php endforeach; ?>
            </select>
            <input type="submit" value="send" />
            </form>
         <!-- Select profile form -->
        <?php endif; ?>

    <?php else: ?>
    <!-- enable analytics link -->
    <p>
        <a href="<?php echo $analytics->getAuthUrl() ?>">Enable Google Analytics on your site</a>
    </p>
    <!-- enable analytics link -->
    <?php endif; ?>
<?php endif;?>
</div>
<style>
.analytics {
	color: #444444; 
	padding: 10px;                   
}
.traffic ul {
	float: left;
	margin-right: 100px;
	overflow: hidden;
	text-align: right;
    width: 200px;
}
.traffic b {
	float: left;
}
.report-box {
	overflow: hidden;
	float: left; 
	width: 200px;
	margin: 10px 0;    
}
.report-box li {
	list-style: none;
	margin: 5px 0;
}
</style>