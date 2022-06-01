<?php
include_once DIR_MODELS . 'Model.php';
class Field
{
    public Model $model;
    public string $attribute;

    private string $placeholder;
    private string $icon;
    private string $type;

    public function __construct(Model $model, string $attribute, string $placeholder, string $icon, string $type)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->placeholder = $placeholder;
        $this->icon = $icon;
        $this->type = $type;
    }

    public function __toString()
    {
        return sprintf(
            '<div class="label label--flex input-box%s">
        <span class="icon %s"></span>
        <input class="input-box__input" type="%s" placeholder="%s" value="%s" name="%s">
    </div>',
            $this->model->has_errors($this->attribute) ? ' is-invalid' : '',
            $this->icon,
            $this->type,
            $this->placeholder,
            $this->model->get_data()[$this->attribute]['value'],
            $this->attribute
        );
    }
}
