<?php 
$this->html->scriptsForLayout .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>'
                               . '<script type="text/javascript" src="/scripts/shared/dashboard.js"></script>';
?>
<div class="analytics" >
<?php if (!$analytics) : ?>
    <p>Analytics not is enabled</p>
<?php else: ?>
    <?php if ($analytics->isAuthenticated()): ?>
        <?php  if ($analytics->profile_id): ?>
            <div id="analytics_report" data-url="<?php echo Mapper::url('/dashboard/analytics_report')?>">
                <?php //echo $this->element('dashboard/analytics_report', compact('analytics')) ?> 
            </div>
        <?php else: ?>
            <?php if ($profiles = $analytics->getProfiles()):?>
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
             <?php else: ?>
                 <p><?php echo $this->html->link(s('sorry, you don\'t have a google analytics account, please click here to create one'), 'http://www.google.com/analytics/', array('target' => '_blank')) ?></p>
             <?php endif; ?>
        <?php endif; ?>
        <p><?php echo $this->html->link(s('Disable analytics'), '/dashboard/disable', array('class' => '')) ?></p>
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

#analytics_report {
    background: url('/images/shared/dashboard/ajax-loading.gif') no-repeat 50% 45%;
    height: 450px;
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
