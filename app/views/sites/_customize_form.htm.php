<fieldset>
    <h2>logotipo</h2>
    <div class="field-group">
        <?php if($site->logo()) echo $this->html->image($site->logo()->link(), array(
            'class' => 'logo'
        )) ?>
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('logo', array(
                'label' => __('Logotipo'),
                'type' => 'file',
                'class' => 'ui-text large'
            )) ?>
            <small>Para melhor aparência do logotipo no seu site mobi, recomendamos utilizar uma imagem com fundo transparente, no formato GIF ou PNG. Tamanho máximo 500kb.</small>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>temas</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <small style="margin: 0 0 15px 0">Você pode customizar a aparência de seu site mobi para deixá-lo com a cara de sua empresa. Escolha um dos temas abaixo e depois personalize-o com as cores da sua empresa.</small>
            
            <div class="theme-picker">
                <h3>Escolha um tema <em>(mais temas em breve)</em></h3>
                <ul>
                    <?php foreach($themes as $slug => $theme): ?>
                        <li>
                            <a href="<?php echo '#' . $slug ?>">
                                <?php echo $this->html->image('themes/' . $slug . '.png') ?>
                                <span><?php echo $theme ?></span>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <?php $keys = array_keys($themes) ?>
            <?php echo $this->form->input('theme', array(
                'type' => 'hidden',
                'value' => $site->theme ? $site->theme : $keys[0]
            )) ?>
            
            <div class="skin-picker">
                <h3>Personalize o tema</h3>
                <ul>
                    <?php foreach($skins as $skin): ?>
                        <li>
                            <a href="<?php echo '#' . $skin ?>" style="background-color:#<?php echo $skin ?>"></a>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <?php echo $this->form->input('skin', array(
                'type' => 'hidden',
                'value' => $site->skin ? $site->skin : $skins[0]
            )) ?>
        </div>
    </div>
</fieldset>