<?php if($success = Session::flash('success')): ?>
    <?php foreach ($success as $message): ?>
    <a href="#" id="success-feedback"><?php echo s($message) ?></a>
    <?php endforeach; ?>
<?php endif ?>

<?php if($error = Session::flash('error')): ?>
    <?php foreach ($error as $message): ?>
    <a href="#" id="error-feedback"><?php echo s($message) ?></a>
    <?php endforeach; ?>
<?php endif ?>