<?php if (!$analytics || !($analytics instanceof Analytics)) : ?>
    <p>Analytics not is enabled</p>
<?php else: ?>
    <?php if ($analytics->isAuthenticated()): ?>
        <?php if ($analytics->getProfile()): ?>
            <?php  var_dump($analytics->getProfile())  ?>
        <?php else: ?>
        <pre><?php // print_r($analytics->getProfiles())  ?> </pre>
            <form action="/dashboard/profile"  method="POST" >
            <p>Select analytics profile</p>
            <select name="profile" >
            <?php foreach($analytics->getProfiles() as $profile): ?>
                <option value="<?php echo $profile['id'], ',', $profile['webPropertyId'] ?>"><?php echo $profile['name'] ?></option>
            <?php endforeach; ?>
            </select>
            <input type="submit" value="send" />
            </form>
        <?php endif; ?>
    <?php else: ?>
    <p>
        <a href="<?php echo $analytics->getAuthUrl() ?>">Enable Google Analytics on your site</a>
    </p>
    <?php endif; ?>
<?php endif;?>
