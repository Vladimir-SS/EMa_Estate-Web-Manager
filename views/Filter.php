<div class="filter-alignment">
    <?php
    echo
    View::render_template('FilterOption', [
        "option_name" => 4,
        "option_type" => "dropdown",
        "option_list" => ["Apartament", "Teren", "Birouri", "Casă", "Vilă"]
    ])
    ?>
</div>