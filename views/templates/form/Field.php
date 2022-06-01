<?php
include_once DIR_MODELS . 'Model.php';
class Field
{
    public Model $model;
    public string $attribute;
    private string $placeholder;

    public function __construct(Model $model, string $attribute, string $placeholder)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->placeholder = $placeholder;
    }

    public function __toString()
    {
        return sprintf(
            '<div class="label label--flex input-box%s">
        <span class="icon %s"></span>
        <input class="input-box__input" type="text" placeholder="%s" value="%s" name="%s">
    </div>',
            $this->model->has_errors($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->placeholder,
            $this->model->get_data()[$this->attribute],
            $this->attribute
        );
    }
}
