<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estence</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../shared/styles/page.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="page-container">
        <?php include_once "../shared/menu.php"; ?>

        <div class="content-container">

            <div class="content">

                <div class="content__filter">
                    <div class="content__box content__box--filter">
                        <h2>Caută anunțuri:</h2>
                        <div class="filter-grid">

                            <div class="icon-field icon-field--filter">
                                Închiriat
                                <?php include "svg/down-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--filter">
                                Apartament
                                <?php include "svg/down-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--filter">
                                Preț
                                <?php include "svg/left-right-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--filter">
                                Mobilă
                                <?php include "svg/down-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--filter">
                                Etaj
                                <?php include "svg/left-right-arrow.svg" ?>
                            </div>

                            <div class="icon-field icon-field--filter">
                                Alte opțiuni
                                <?php include "svg/right-arrow.svg" ?>
                            </div>

                            <button class="icon-field search" type="search">
                                <?php include "svg/magnifying-glass.svg" ?>
                                Caută acum 20 132 anunțuri
                            </button>
                        </div>
                        <!-- /.filter-grid -->
                    </div>
                    <!-- /.content__box content__box--filter -->
                    <img src="./img/ap-header1.png" alt="Apartment1">
                    <img src="./img/ap-header2.png" alt="Apartment2">
                    <img src="./img/ap-header3.png" alt="Apartment3">
                    <img src="./img/ap-header4.png" alt="Apartment4">
                </div>
                <!-- /.content__filter -->

                <div class="content__grid">
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                    <?php include "item.php" ?>
                </div>

                <div class="footer">

                </div>
                <!-- /.footer -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.page -->
    </div>
    <!-- /.page-container -->

</body>

</html>