<?php include_once DIR_TEMPLATES . '/form/Form.php'; ?>
<div class="content__box content__box--form">

    <?php $form = Form::begin('content__box__form', '/create-ad', 'post', 'multipart/form-data') ?>
    <h2>Adaugă titlu anunț*</h2>

    <?php
    echo $form->field($model, 'title', 'Ex. Apartament 3 camere Copou Bloc Nou')->get_simple_input();
    echo $form->field($model, 'address', 'Introdu locația*', 'icon-pin');
    //TODO: add curency
    echo $form->field($model, 'price', 'Preț')->get_simple_input();
    echo $form->field($model, 'surface', "Suprafață")->get_simple_input();
    ?>

    <fieldset id="dropdown-container">

    </fieldset>

    <fieldset id="type-specific-form">
        <fieldset id="residential-specific">
            <!-- rooms, floor -->
            <?php
            echo $form->field($model, 'rooms', "Numărul de camere")->get_simple_input();
            echo $form->field($model, 'floor', "Etaj")->get_simple_input();
            ?>
        </fieldset>
        <fieldset id="building-specific">
            <!-- bathrooms, parking_lots, built_on -->
            <?php
            echo $form->field($model, 'bathrooms', "Numărul de băi")->get_simple_input();
            echo $form->field($model, 'parking_lots', "Numărul locurilor de parcare")->get_simple_input();
            echo $form->field($model, 'built_on', "Anul de construcție al clădirii")->get_simple_input();
            ?>
        </fieldset>
        <fieldset id="house-specific">
            <!-- basement, floor-->
            <?php
            echo $form->field($model, 'floor', "Numărul de etaje")->get_simple_input();
            ?>
        </fieldset>

    </fieldset>

    <h2>Imagini</h2>

    <div class="images" id="images">
        <label class="label images__add-img image-container" onclick="">
            <span id="plus-icon" class="icon icon-plus"></span>
            <input id="add-image-input" type="file" accept="image/*" name="images" multiple>
        </label>
    </div>

    <h2>Descriere*</h2>
    <?php
    echo $form->field($model, 'description', 'Descriere')->get_textarea();
    ?>

    <button class="label label--flex label--important" type="submit" onclick>
        <span class="icon icon-conn-arr"></span>
        Creează anunț
    </button>

    <?php Form::end() ?>

</div>