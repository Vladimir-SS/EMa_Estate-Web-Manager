<?php include_once DIR_TEMPLATES . '/form/Form.php'; ?>
<div class="content__box content__box--form">

    <h2>Conectează-te</h2>
    <button class="label label--flex label--social label--social--google" type="button" onclick>
        <span class="icon icon-google"></span>
        Conectează-te cu contul tău Google
    </button>

    <button class="label label--flex label--social label--social--facebook" type="button" onclick>
        <span class="icon icon-facebook"></span>
        Conectează-te cu contul tău Facebook
    </button>
    <?php $form = Form::begin('content__box__form', '/login', 'post') ?>

    <p>Completeaza campurile de mai jos pentru a te conecta.</p>
    <?php

    echo $form->field($model, 'email_or_phone', 'E-mail sau Telefon', 'icon-person');
    echo $model->has_errors('email_or_phone') ? 'Error' : '';

    echo $form->field($model, 'password', 'Introdu parola', 'icon-pass', 'password');
    ?>

    <p> <a class="hlink" href="#">Ați uitat parola?</a></p>

    <p>Nu ai cont?
        <a class="hlink" href="/register"> Creează-ți cont</a>
    </p>

    <p class="term-and-cond__paragraph">Prin actiunea de conectare esti de acord cu
        <a class="hlink" href="#">Termenii și Condițiile</a>, cât și
        <a class="hlink" href="#">Politica de confidentialitate</a>
    </p>

    <button class="label label--flex label--important" type="submit" onclick>
        <span class="icon icon-conn-arr"></span>
        Conectare
    </button>

    <?php Form::end() ?>
</div>