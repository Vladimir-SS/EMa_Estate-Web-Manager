<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;1,100;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../shared/styles/page.css">
</head>

<body>
    <div class="page-container">
        <?php include_once "../shared/menu.html"; ?>

        <div class="content-container responsive-container">
            <div class="content">

                <div class="container">

                    <div class="fast-conn-buttons">

                        <p class="conn-heading">Creeaza-ti cont</p>
                        <button class="btn btn--gmail" type="conn-gmail">
                            <img src="svg/gmail.svg" alt="Gmail icon">
                            Conecteaza-te cu contul tau Google
                        </button>
                        <!-- /.btn btn--gmail -->

                        <button class="btn btn--facebook" type="conn-facebook">
                            <img src="svg/facebook.svg" alt="Facebook icon">
                            Conecteaza-te cu contul tau Facebook
                        </button>
                        <!-- /.btn btn--gmail -->
                    </div>
                    <!-- /.fast--conn-buttons -->
                    <form class="container__form" action="action_login.php" method="get">

                        <p>Completeaza campurile de mai jos pentru a te inregistra.</p>
                        <div class="input-box">
                            <img src="svg/person.svg" alt="Person icon">
                            <input class="input-box__input" type="text" placeholder="Nume si prenume" name="flname" required>
                        </div>
                        <!-- /.input-box -->

                        <div class="input-box">
                            <img src="svg/phone.svg" alt="Phone icon">
                            <input class="input-box__input" type="tel" placeholder="Telefon" name="phone" required>
                        </div>
                        <!-- /.input-box -->

                        <div class="input-box">
                            <img src="svg/mail.svg" alt="Mail icon">
                            <input class="input-box__input" type="email" placeholder="E-mail" name="email" required>
                        </div>
                        <!-- /.input-box -->

                        <div class="input-box">
                            <img src="svg/pass.svg" alt="Lock icon">
                            <input class="input-box__input input-box__input--pwd" type="password" placeholder="Introdu parola" name="psw" required>
                        </div>
                        <!-- /.input-box -->

                        <div class="input-box">
                            <img src="svg/pass.svg" alt="Lock icon">
                            <input class="input-box__input input-box__input--pwd" type="password" placeholder="Confirma parola" name="psw" required>
                        </div>
                        <!-- /.input-box -->

                        <p>Ai deja cont? <a class="hlink" href="../login-register/login.php"> Conecteaza-te</a></p>

                        <button class="btn" type="create">
                            <img src="svg/conn-arr.svg" alt="Connect arrow icon">
                            Creeaza-ti contul
                        </button>
                        <!-- /.btn -->


                    </form>
                    <!-- /.container__form -->
                </div>
                <!-- /.container -->

            </div>
        </div>
        <!-- /.page -->
    </div>
    <!-- /.page-container -->

</body>

</html>