<div class="analytics" >
<?php if (!$analytics) : ?>
    <p>Analytics not is enabled</p>
<?php else: ?>
    <?php if ($analytics->isAuthenticated()): ?>
        <?php if ($analytics->profile_id): ?>
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
                    <li><b><?php echo  (int)$totals['ga:avgTimeOnSite']; ?></b> <?php echo s('Avg. Time on site') ?></li>
                    <li><b><?php echo number_format($totals['ga:percentNewVisits'], 2); ?></b> <?php echo s('%% New Visits') ?></li>
                </ul>
            </div>
            <!-- traffic -->
            <!-- top pages -->
            <div class="report-box" style="float:none; width:auto;">
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
                    <li><?php echo s('%s - <i>%u visits</i>',$system,$visits) ?></li>
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
                    <li><?php echo s('%s - <i>%u visits</i>',$screem,$visits) ?></li>
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
.traffic ul {
	float: left;
	margin-right: 20px;
	overflow: hidden;
	text-align: right;
    width: 250px;
}
.traffic b {
	float: left;
}
.report-box {
	color: #444444;
	float: left; 
	width: 200px;
	margin: 10px;    
}
.report-box li {
	list-style: none;
	margin: 5px 0;
}
</style>