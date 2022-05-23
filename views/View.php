<?php
include_once DIR_VIEWS . "HtmlContainer.php";

class View
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
        return HtmlContainer::CustomTag("", "")
            ->setPath(DIR_TEMPLATES)
            ->setExtension("php")
            ->add($file_name);
    }

    public static function render_style(String $file_name): HtmlContainer
    {
        return HtmlContainer::HtmlElement("style")
            ->setPath(DIR_VIEWS . "style/build/")
            ->setExtension("css")
            ->add($file_name);
    }

    public static function render_script(String $file_name): HtmlContainer
    {
        return HtmlContainer::HtmlElement("script")
            ->setPath(DIR_VIEWS . "script/build/")
            ->setExtension("js")
            ->add($file_name);
    }

    public static function render_vector(String $file_name)
    {
        return HtmlContainer::CustomTag("", "")
            ->setPath(DIR_VIEWS . "svg/")
            ->setExtension("svg")
            ->add($file_name);
    }
}
