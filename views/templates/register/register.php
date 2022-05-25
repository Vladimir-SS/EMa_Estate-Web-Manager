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

    <form class="content__box__form" action="/register" method="post">

        <p>Completează câmpurile de mai jos pentru a te înregistra.</p>
        <div class="double-input-row">
            <div class="label label--flex input-box">
                <span class="icon icon-person"></span>
                <input class="input-box__input" type="text" placeholder="Nume" name="last_name" required>
            </div>

            <div class="label label--flex input-box">
                <span class="icon icon-person"></span>
                <input class="input-box__input" type="text" placeholder="Prenume" name="first_name" required>
            </div>
        </div>
        <div class="label label--flex input-box">
            <span class="icon icon-phone"></span>
            <input class="input-box__input" type="tel" placeholder="Telefon" name="phone" required>
        </div>

        <div class="label label--flex input-box">
            <span class="icon icon-mail"></span>
            <input class="input-box__input" type="email" placeholder="E-mail" name="email" required>
        </div>

        <div class="label label--flex input-box">
            <span class="icon icon-pass"></span>
            <input class="input-box__input" type="password" placeholder="Introdu parola" name="password" required>
        </div>

        <div class="label label--flex input-box">
            <span class="icon icon-pass"></span>
            <input class="input-box__input" type="password" placeholder="Confirmă parola" name="confirm-password" required>
        </div>

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

    </form>
</div>