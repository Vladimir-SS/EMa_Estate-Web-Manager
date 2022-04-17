<div class="splited">
    <div class="flex-1">
        <div class="content-filter">

            <div class="icon-field icon-field--option icon-field--option--selected" onclick>
                Închiriat
                <?php include "../shared/svg/down-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--option icon-field--option--selected" onclick>
                Apartament
                <?php include "../shared/svg/down-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--option" onclick>
                Preț
                <?php include "../shared/svg/left-right-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--option" onclick>
                Mobilă
                <?php include "../shared/svg/down-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--option" onclick>
                Etaj
                <?php include "../shared/svg/left-right-arrow.svg" ?>
            </div>

            <div class="icon-field icon-field--option" onclick>
                Alte opțiuni
                <?php include "../shared/svg/right-arrow.svg" ?>
            </div>

            <button class="icon-field icon-field--important" type="search" onclick>
                <?php include "../shared/svg/magnifying-glass.svg" ?>
                Caută acum 20 132 anunțuri
            </button>
        </div>
        <!-- /.content-filter -->
        <div id="items">
            <?php
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            include "item.php";
            ?>
        </div>
    </div>
    <!-- /.flex-1 -->
    <div class="side-map content__box">
        <div class="pin-container">
            <div class="pin">
                123 000
            </div>
        </div>
    </div>
    <!-- /.flex-1 side-map -->
</div>
<!-- /.splited -->