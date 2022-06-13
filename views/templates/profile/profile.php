<?php include_once DIR_TEMPLATES . '/form/Form.php'; ?>
<div class="splited">

    <div class="content__box">
        <div class="content__box--profile">
            <div class="content__box--profile__img" id="avatar">
                <?php
                $data = $image ?? $image->load();
                echo base64_encode($data);
                ?>
            </div>
            <?php
            echo sprintf(
                '<h2> %s %s (%s) </h2>',
                $model->get_data()['LAST_NAME']['value'],
                $model->get_data()['FIRST_NAME']['value'],
                $model->get_data()['BUSINESS_NAME']['value']
            );
            if ($model->get_data()['BUSINESS_NAME']['value'] != null)
                echo '<p>Agent Imobiliar</p>'
            ?>

            <p class="secondary"> Pe Estence din Februarie 2018 </p>
        </div>

        <div class="content__box--form">
            <?php $form = Form::begin('content__box__form', '/profile', 'post') ?>
            <div class="double-input-row">
                <?php
                echo $form->field($model, 'LAST_NAME', $model->get_data()['LAST_NAME']['value'], 'icon-person');
                echo $form->field($model, 'FIRST_NAME', $model->get_data()['FIRST_NAME']['value'], 'icon-person');
                ?>
            </div>
            <?php
            echo $form->field($model, 'PHONE', $model->get_data()['PHONE']['value'], 'icon-phone', 'tel');
            echo $form->field($model, 'EMAIL', $model->get_data()['EMAIL']['value'], 'icon-mail');
            echo $form->field($model, 'BUSINESS_NAME', $model->get_data()['BUSINESS_NAME']['value'] ?? 'Adauga business', 'icon-mail');
            ?>
            <p>Schimbă parola</p>
            <?php
            echo $form->field($model, 'OLD_PASSWORD', 'Introdu parola curentă', 'icon-pass', 'password');
            echo $form->field($model, 'PASSWORD', 'Introdu parola nouă', 'icon-pass', 'password');
            echo $form->field($model, 'CONFIRM_PASSWORD', 'Confirmă parola nouă', 'icon-pass', 'password');
            ?>

            <button class="label label--flex label--important" type="submit" onclick>
                <span class="icon icon-conn-arr"></span>
                Actualizare profil
            </button>

            <?php Form::end() ?>
        </div>
    </div>

    <div class="flex-1">
        <div class="content__box">
            <div class="horizontal-buttons">
                <h2>Anunțuri salvate</h2>
                <h2>Anunțurile tale</h2>
            </div>
            <div id="items">
                <?php echo Renderer::render_content("item")->add("item")->add("item")->add("item")->add("item")->add("item");
                ?>
            </div>
        </div>
    </div>
</div>