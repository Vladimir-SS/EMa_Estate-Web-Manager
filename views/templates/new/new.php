<div class="content-box content__box--form">

    <form class="content__box__form" action="/new" method="post">
        <h2>Adaugă titlu anunț*</h2>
        <div class="label label--flex input-box">
            <input class="input-box__input" type="text" placeholder="Ex. Apartament 3 camere Copou Bloc Nou" name="title" required>
        </div>

        <div class="label label--flex input-box">
            <span class="icon icon-pin"></span>
            <input class="input-box__input" type="text" placeholder="Introdu locația*" name="location" required>
        </div>

        <div class="content-filter">

            <div class="label label--flex icon-field--setter icon-field--setter--selected" onclick>
                Închiriat
                <span class="icon icon-down-arrow"></span>
            </div>

            <div class="label label--flex icon-field--setter icon-field--setter--selected" onclick>
                Apartament
                <span class="icon icon-down-arrow"></span>
            </div>

            <div class="icon-field icon-field--setter" onclick>
                Preț
                <span class="icon icon-left-right-arrow"></span>
            </div>

            <div class="icon-field icon-field--setter" onclick>
                Mobilă
                <?php include "../shared/svg/down-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--setter" onclick>
                Etaj
                <?php include "../shared/svg/left-right-arrow.svg" ?>
            </div>

        </div>
        <!-- /.content-filter -->
        <h2>Imagini</h2>

        <div class="images">
            <div class="images__add-img image-container">
                <div class="image" style="background-image:url(./img/img1.png);"></div>
            </div>

            <div class="images__add-img image-container">
                <div class="image" style="background-image:url(./img/img2.jpg);"></div>
            </div>

            <div class="images__add-img">
                <?php include "../shared/svg/plus.svg" ?>
            </div>
        </div>
        <!-- /.images -->

        <h2>Descriere*</h2>
        <textarea class="desc" maxlength="4000" name="desc" required>
                        </textarea>

        <button class="label label--flex label--important" type="submit" onclick>
            <span class="icon icon-conn-arr"></span>
            Creează anunț
        </button>
        <!-- /.icon-field submit -->

    </form>
</div>