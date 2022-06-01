<?php
include_once DIR_TEMPLATES . '/form/Field.php';

class Form
{
    public static function begin($class, $action, $method)
    {
        echo sprintf('<form class="%s" action="%s" method="%s">', $class, $action, $method);
        return new Form();
    }
    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute, $placeholder = '', $icon = '', $type = 'text')
    {
        return new Field($model, $attribute, $placeholder, $icon, $type);
    }
}
