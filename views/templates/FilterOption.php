<?php
include DIR_VIEWS . "FilterOption/$option_type.php";
?>

<div id="tip" class="filter-option <?php echo $option_classes; ?>">

    <div class="label icon-field" onclick="OptionHandler.revealOption(this)">
        <?php
        echo $option_name;
        echo View::render_vector($option_vector);
        ?>
    </div>

    <ul class="content__box">
        <?php
        echo $option_content;
        ?>
    </ul>
</div>