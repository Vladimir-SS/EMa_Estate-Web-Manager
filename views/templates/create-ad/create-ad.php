<div class="content-box content__box--form">

    <form class="content__box__form" action="/create-ad" method="post">
        <h2>Adaugă titlu anunț*</h2>
        <div class="label label--flex input-box">
            <input class="input-box__input" type="text" placeholder="Ex. Apartament 3 camere Copou Bloc Nou" name="title" required>
        </div>

        <div class="label label--flex input-box">
            <span class="icon icon-pin"></span>
            <input class="input-box__input" type="text" placeholder="Introdu locația*" name="location" required>
        </div>

        <div class="content-filter">
            <?php echo View::render_template("Filter")
            ?>
            <!-- <div class="label label--flex icon-field--setter icon-field--setter--selected" onclick>
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
                <span class="icon icon-down-arrow"></span>
            </div>

            <div class="icon-field icon-field--setter" onclick>
                Etaj
                <span class="icon icon-left-right-arrow"></span>
            </div> -->

        </div>

        <h2>Imagini</h2>

        <div class="images" id="images">
            <!-- max 5 imagini -->

            <label class="images__add-img image-container" id="add_file" onclick="">
                <span class="icon icon-plus"></span>
                <input type="file" id="image-input" accept="image/*"> </input>
            </label>

        </div>

        <h2>Descriere*</h2>
        <textarea class="desc" maxlength="4000" name="desc" placeholder="Descriere" required></textarea>


        <button class="label label--flex label--important" type="submit" onclick>
            <span class="icon icon-conn-arr"></span>
            Creează anunț
        </button>

    </form>
</div>