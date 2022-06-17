<?php

class Renderer
{
    public static function render_file(String $file_path)
    {
        ob_start();
        include $file_path;

        return ob_get_clean();
    }

    public static function render_template(String $template_name, array $props = array())
    {
        extract($props);

        ob_start();
        include DIR_TEMPLATES . $template_name . '.php';

        return ob_get_clean();
    }

    public static function render_content(String ...$files): String
    {
        $rv = "";
        foreach ($files as $file)
            $rv .= Renderer::render_file(DIR_TEMPLATES . "$file.php");

        return $rv;
    }

    public static function render_styles(String ...$files): String
    {
        $rv = "";
        foreach ($files as $file)
            $rv .= "<link rel=\"stylesheet\" href=\"/styles/$file.css\">";

        return $rv;
    }

    public static function render_scripts(String ...$files): String
    {
        $rv = "";
        foreach ($files as $file)
            $rv .= "<script src=\"/scripts/$file.js\"></script>";

        return $rv;
    }
}
