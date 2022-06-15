<?php
include_once DIR_VIEWS . "HtmlContainer.php";

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

    public static function render_content(String $file_name): HtmlContainer
    {
        return HtmlContainer::custom_tag("", "")
            ->set_path(DIR_TEMPLATES)
            ->set_extension("php")
            ->add($file_name);
    }

    public static function render_style(String $file_name): HtmlContainer
    {
        return HtmlContainer::html_element("style")
            ->set_path(DIR_VIEWS . "style/build/")
            ->set_extension("css")
            ->add($file_name);
    }

    public static function render_script(String $file_name): HtmlContainer
    {
        return HtmlContainer::html_element("script")
            ->set_path(DIR_VIEWS . "script/build/")
            ->set_extension("js")
            ->add($file_name);
    }

    public static function render_scripts(...$files)
    {
        $data = "";
        foreach ($files as $file_name) {
            $data .= self::render_script($file_name);
        }
        return $data;
    }

    public static function render_vector(String $file_name)
    {
        return HtmlContainer::custom_tag("", "")
            ->set_path(DIR_VIEWS . "svg/")
            ->set_extension("svg")
            ->add($file_name);
    }
}
