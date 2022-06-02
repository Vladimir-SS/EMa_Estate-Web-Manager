<?php
include_once DIR_TEMPLATES . '/form/Field.php';

class Form
{
    public static function begin($class, $action, $method, $enctype = 'application/x-www-form-urlencoded')
    {
        echo sprintf('<form class="%s" action="%s" method="%s" enctype="%s">', $class, $action, $method, $enctype);
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
