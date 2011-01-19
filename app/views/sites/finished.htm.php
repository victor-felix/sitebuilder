<?php
$this->layout = "register";
$this->showTitle = false;
?>
<div class="registration-finished">
    <?php echo $this->html->image('register/finished.png', array(
        'alt' => 'Registro completado com sucesso!'
    )); ?>
    <h2>Parabéns! Seu site <strong>mobi</strong> já pode ser acessado em <?php echo $this->html->link('http://google.com') ?></h2>
    <div class="next-steps">
        <h3></h3>
        <p></p>
        <ul>
            <li><?php echo $this->html->link('Adicione o cardápio de seu restaurante ›', '') ?></li>
            <li><?php echo $this->html->link('Forneça mais detalhes sobre sua empresa ›', '') ?></li>
            
        </ul>
    </div>
</div>