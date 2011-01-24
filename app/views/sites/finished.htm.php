<?php $this->layout = 'register' ?>
<?php $this->showTitle = false ?>
<div class="registration-finished">
    <?php echo $this->html->image('register/finished.png', array(
        'alt' => __('Registro completado com sucesso!')
    )); ?>
    <h2><?php echo __('Parabéns! Seu site <strong>mobi</strong> já pode ser acessado em %s', $this->html->link($site->link())) ?></h2>
    <div class="next-steps">
        <h3><?php echo __('Próximas etapas') ?></h3>
        <p><?php echo __('Seu site mobi já está online, mas você ainda pode continuar a melhorá-lo.') ?></p>
        <ul>
            <li><?php echo $this->html->link(__('Adicione o cardápio de seu restaurante ›'), '/categories') ?></li>
            <li><?php echo $this->html->link(__('Forneça mais detalhes sobre sua empresa ›'), '/settings') ?></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>