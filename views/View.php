<?php

class View
{
    private static function render_file(String $file_name)
    {
        ob_start();
        include $file_name;

        return ob_get_clean();
    }

    public static function render_template(String $template_name, array $props = array())
    {
        extract($props);

        ob_start();
        include DIR_TEMPLATES . $template_name . '.php';

        return ob_get_clean();
    }

    public static function render_style(String $file_name)
    {
        return "<style>" . View::render_file(DIR_VIEWS . "style/build/" . $file_name . ".css") . "</style>";
    }

    public static function render_script(String $file_name)
    {
        return "<script>" . View::render_file(DIR_VIEWS . "script/build/" . $file_name . ".js") . "</script>";
    }

    public static function render_vector(String $vector_name)
    {
        return View::render_file(DIR_VIEWS . "svg/" . $vector_name . '.svg');
    }
}
