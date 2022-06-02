<div class="splited">
    <div class="flex-1">
        <div class="content-filter">
            <?php echo View::render_content("Filter")
            ?>
        </div>

        <div id="items">
            <?php echo View::render_content("item")->add("item")->add("item")->add("item")->add("item")->add("item");
            ?>
        </div>
    </div>
    <div class="side-map">
        <div class="pin-container">
            <div class="pin">
                123 000
            </div>
        </div>
    </div>
</div>