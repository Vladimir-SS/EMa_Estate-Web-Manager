<?php
class FileRequest
{
    private static function getHeader(String $extension): string
    {
        switch ($extension) {

            case 'svg':
                return 'image/svg+xml';
            case 'ttf':
                return 'application/x-font-ttf';
            case 'otf':
                return 'application/x-font-opentype';
            case 'woff':
                return 'application/font-woff';
            case 'eot':
                return 'application/vnd.ms-fontobject';

            default:
                return 'text/plain';
        }
    }

    public static function respond(string $path): void
    {
        if (!file_exists($path))
            return;

        header('Content-Type: ' . mime_content_type($path));
        echo file_get_contents($path);
    }
}
