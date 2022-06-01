<?php include_once DIR_TEMPLATES . '/form/Form.php'; ?>

<div class="content__box content__box--form">

    <h2>Creează-ți cont</h2>
    <button class="label label--flex label--social label--social--google" type="button" onclick>
        <span class="icon icon-google"></span>
        Conectează-te cu contul tău Google
    </button>

    <button class="label label--flex label--social label--social--facebook" type="button" onclick>
        <span class="icon icon-facebook"></span>
        Conectează-te cu contul tău Facebook
    </button>

    <?php $form = Form::begin('content__box__form', '/register', 'post') ?>

    <p>Completează câmpurile de mai jos pentru a te înregistra.</p>
    <div class="double-input-row">
        <?php
        echo $form->field($model, 'last_name', 'Nume', 'icon-person');
        echo $form->field($model, 'first_name', 'Prenume', 'icon-person');
        ?>
    </div>
    <?php
    echo $form->field($model, 'phone', 'Telefon', 'icon-phone', 'tel');
    echo $form->field($model, 'email', 'E-mail', 'icon-mail', 'email');
    echo $form->field($model, 'password', 'Introdu parola', 'icon-pass', 'password');
    echo $form->field($model, 'confirm-password', 'Confirmă parola', 'icon-pass', 'password');
    ?>

    <p>Ai deja cont?
        <a class="hlink" href="/login"> Conectează-te</a>
    </p>

    <div class="term-and-cond">
        <span class="icon icon-checkbox-checked" onclick="checkHandler(this)"></span>
        <p class="term-and-cond__paragraph">Sunt de acord cu
            <a class="hlink" href="#">Termenii și Condițiile</a>, cât și <br> <a class="hlink" href="#">Politica de confidenţialitate</a>
        </p>
    </div>

    <button class="label label--flex label--important" name="register" type="submit" onclick>
        <span class="icon icon-conn-arr"></span>
        Creează-ți contul
    </button>

    <?php Form::end() ?>
</div>