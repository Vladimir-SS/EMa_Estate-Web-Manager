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

    private function error_message()
    {
        return sprintf('<span class="error"> %s </span>', $this->model->errors[$this->attribute]);
    }

    public function __toString()
    {
        if ($this->model->has_errors($this->attribute))
            return sprintf(
                '<div> <div class="label label--flex label--error input-box input-box--is-invalid">
                    <span class="icon icon-error"></span>
                    <input class="input-box__input" type="%s" placeholder="%s" value="%s" name="%s">
                </div> %s </div>',
                $this->type,
                $this->placeholder,
                $this->model->get_data()[$this->attribute]['value'],
                $this->attribute,
                $this->error_message()
            );

        return sprintf(
            '<div class="label label--flex input-box">
                <span class="icon %s"></span>
                <input class="input-box__input" type="%s" placeholder="%s" value="%s" name="%s">
            </div>',
            $this->icon,
            $this->type,
            $this->placeholder,
            $this->model->get_data()[$this->attribute]['value'],
            $this->attribute
        );
    }
}
