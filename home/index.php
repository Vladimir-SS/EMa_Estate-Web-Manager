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
        <?php include_once "../shared/menu.html"; ?>

        <div class="content-container">

            <div class="content">

                <div class="content__box content__box--filter">
                    <h2>Caută anunțuri:</h2>
                    <div class="filter-grid">

                        <div class="icon-field icon-field--announces">
                            Închiriat
                            <?php include "svg/down-arrow.svg" ?>
                        </div>

                        <div class="icon-field icon-field--announces">
                            Apartament
                            <?php include "svg/down-arrow.svg" ?>
                        </div>

                        <div class="icon-field icon-field--announces">
                            Preț
                            <?php include "svg/left-right-arrow.svg" ?>
                        </div>

                        <div class="icon-field icon-field--announces">
                            Mobilă
                            <?php include "svg/down-arrow.svg" ?>
                        </div>

                        <div class="icon-field icon-field--announces">
                            Etaj
                            <?php include "svg/left-right-arrow.svg" ?>
                        </div>

                        <div class="icon-field icon-field--announces">
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
            </div>
            <!-- /.content -->
        </div>
        <!-- /.page -->
    </div>
    <!-- /.page-container -->

</body>

</html>