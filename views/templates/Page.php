<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Estence - Cel mai bun website de imobiliare din țară">
    <title><?php echo $title ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php
    echo Renderer::render_styles("page");
    echo $styles;
    ?>
</head>

<body>
    <div class="page-container ">
        <?php echo Renderer::render_template("Menu") ?>
        <div class="content-container">
            <div class="content responsive-container">
                <?php echo $content; ?>
            </div>
        </div>
    </div>

    <?php
    echo Renderer::render_scripts("page");
    echo $scripts;
    ?>
</body>

</html>