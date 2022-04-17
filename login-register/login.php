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
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div class="page-container">
        <?php include_once "../shared/menu.php"; ?>

        <div class="content-container responsive-container">
            <div class="content">

                <div class="content__box content__box--login">

                    <h2>Conectează-te</h2>
                    <button class="icon-field icon-field--gmail icon-field--social" type="conn-gmail" onclick>
                        <?php include "../shared/svg/gmail.svg" ?>
                        Conectează-te cu contul tău Google
                    </button>
                    <!-- /.icon-field icon-field--gmail icon-field--social -->

                    <button class="icon-field icon-field--facebook icon-field--social" type="conn-facebook" onclick>
                        <?php include "../shared/svg/facebook.svg" ?>
                        Conectează-te cu contul tău Facebook
                    </button>
                    <!-- /.icon-field icon-field--facebook icon-field--social -->

                    <form class="content__box__form" action="action_login.php" method="get">

                        <p>Completeaza campurile de mai jos pentru a te conecta.</p>
                        <div class="icon-field input-box">
                            <?php include "../shared/svg/person.svg" ?>
                            <input class="input-box__input" type="text" placeholder="E-mail sau Telefon" name="mail-or-phone" required>
                        </div>
                        <!-- /.icon-field input-box -->

                        <div class="icon-field input-box">
                            <?php include "../shared/svg/pass.svg" ?>
                            <input class="input-box__input input-box__input--pwd" type="password" placeholder="Introdu parola" name="psw" required>
                        </div>
                        <!-- /.icon-field input-box -->

                        <p> <a class="hlink" href="#">Ați uitat parola?</a></p>

                        <p>Nu ai cont?
                            <a class="hlink" href="../login-register/register.php"> Creează-ți cont</a>
                        </p>

                        <p class="term-and-cond__paragraph">Prin actiunea de conectare esti de acord cu
                            <a class="hlink" href="#">Termenii și Condițiile</a>, cât și
                            <a class="hlink" href="#">Politica de confidentialitate</a>
                        </p>

                        <button class="icon-field icon-field--important" type="submit" onclick>
                            <?php include "../shared/svg/conn-arr.svg" ?>
                            Conectare
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