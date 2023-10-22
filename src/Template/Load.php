<?php

namespace Bethropolis\Bloom\Template;


class Load
{
    public static function fetch($path): string
    {
        $filePath = self::fetchFilePath($path);
        $content = self::fetchContent($filePath);

        return $content;
    }

    private static function fetchContent($filePath): string
    {
        if (file_exists($filePath)) {
          return file_get_contents($filePath);  
        }
        return new \Exception("File not found: $filePath");
    }

    private static function fetchFilePath($path)
    {
        $path = BLOOM_TEMPLATE_PATH . "/" . $path;
        if (self::ends_with($path, '.bloom')) {
            return $path;
        }
        return $path . '.bloom';
    }

    private static function ends_with($string, $suffix)
    {
        return $suffix === '' || substr($string, -strlen($suffix)) === $suffix;
    }
}