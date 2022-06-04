<?php

include_once DIR_VIEWS . "Renderer.php";

class View
{
    private string $title = 'Pagină inexistentă';
    private string $content;
    private string $styles = '';
    private string $scripts = '';

    public function render()
    {
        echo Renderer::render_template("Page", [
            "title" => $this->title,
            "content" =>  $this->content,
            "styles" =>  $this->styles,
            "scripts" =>  $this->scripts
        ]);
    }

    public function set_title(string $title)
    {
        $this->title = $title;
    }

    public function set_content($content)
    {
        $this->content = $content;
    }

    public function set_styles(string $styless)
    {
        $this->styles = $styless;
    }

    public function set_scripts($scripts)
    {
        $this->scripts = $scripts;
    }
}
