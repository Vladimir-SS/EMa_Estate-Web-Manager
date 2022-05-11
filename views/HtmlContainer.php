<?php
class HtmlContainer
{
    private string $open_tag;
    private string $close_tag;
    private string $content;
    private string $path;
    private string $extension;

    private function __construct(string $open_tag, string $close_tag)
    {
        $this->open_tag = $open_tag;
        $this->close_tag = $close_tag;
        $this->content = "";
        $this->path = "";
        $this->extension = "";
    }

    public static function HtmlElement(string $tag): HtmlContainer
    {
        return new HtmlContainer("<$tag>", "</$tag>");
    }

    public static function CustomTag(string $open_tag, string $close_tag): HtmlContainer
    {
        return new HtmlContainer($open_tag, $close_tag);
    }

    public function add(string $file): HtmlContainer
    {
        $this->content .= View::render_file($this->path . $file . $this->extension);

        return $this;
    }

    public function setPath(string $path): HtmlContainer
    {
        $this->path = $path;

        return $this;
    }

    public function setExtension(string $extension): HtmlContainer
    {
        $this->extension = ".$extension";

        return $this;
    }

    public function __toString(): string
    {
        return $this->open_tag . $this->content . $this->close_tag;
    }
}
