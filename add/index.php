<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../shared/styles/page.css">
    <link rel="stylesheet" href="../shared/styles/form.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="page-container">
        <?php include_once "../shared/menu.php"; ?>

        <div class="content-container responsive-container">
            <div class="content">

                <div class="content__box content__box--form">

                    <form class="content__box__form" action="action_add.php" method="get">
                        <h2>Adaugă titlu anunț*</h2>
                        <div class="icon-field input-box">
                            <input class="input-box__input" type="text" placeholder="Ex. Apartament 3 camere Copou Bloc Nou" name="title" required>
                        </div>
                        <!-- /.icon-field input-box -->
                        <div class="icon-field input-box">
                            <?php include "../shared/svg/pin.svg" ?>
                            <input class="input-box__input" type="text" placeholder="Introdu locația*" name="location" required>
                        </div>
                        <!-- /.icon-field input-box -->
                        <div class="content-filter">

                            <div class="icon-field icon-field--setter icon-field--setter--selected" onclick>
                                Închiriat
                                <?php include "../shared/svg/down-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--setter icon-field--setter--selected" onclick>
                                Apartament
                                <?php include "../shared/svg/down-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--setter" onclick>
                                Preț
                                <?php include "../shared/svg/left-right-arrow.svg" ?>
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
                        <textarea class="desc" maxlength="5000" name="desc" required>
                        </textarea>

                        <button class="icon-field icon-field--important" type="submit" onclick>
                            <?php include "../shared/svg/conn-arr.svg" ?>
                            Creează anunț
                        </button>
                        <!-- /.icon-field submit -->


                    </form>
                    <!-- /.content__box__form -->
                </div>
                <!-- /.content__box content__box--login -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.page -->
    </div>
    <!-- /.page-container -->


    <script src="../shared/menu.js"></script>

</body>

</html>