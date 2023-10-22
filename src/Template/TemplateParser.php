<?php

namespace Bethropolis\Bloom\Template;

use Bethropolis\Bloom\Template\Load;
use Bethropolis\Bloom\Template\BlockReferenceParser;

class TemplateParser
{
    private static array $data = [];

    public static function parseTemplate($template, $data = [])
    {
        // Split the template into single and block references
        $template = self::parseIncludes($template);
        $singleReferences = self::extractSingleReferences($template);
        $blockReferences = self::extractBlockReferences($template);

        // Call the appropriate parser for each reference type
        $template = self::parseSingleReferences($template, $singleReferences, $data);
        $template = self::parseBlockReferences($template, $blockReferences, $data);

        return $template;
    }

    private static function parseIncludes($template)
    {
        $template = preg_replace_callback('/@include\([\'"](.+?)[\'"]\)/', function ($matches) {
            $includedTemplate = self::loadTemplate($matches[1]);
            return $includedTemplate;
        }, $template);


        return $template;
    }

    // private static function parseData($template, $data)
    // {
    //     foreach ($data as $key => $value) {
    //         $placeholder = '{{ ' . $key . ' }}';
    //         $template = str_replace($placeholder, $value, $template);
    //     }

    //     return $template;
    // }

    private static function loadTemplate($templatePath)
    {

        $template = Load::fetch($templatePath);
        $template = self::parseTemplate($template, self::$data);

        return $template;
    }

    private static function extractSingleReferences($template)
    {
        // Implement code to extract single references
        // Use regular expressions to find and collect single references
        preg_match_all('/{{\s*([^{}]+)\s*}}/', $template, $matches);
        return $matches[1];
    }

    private static function extractBlockReferences($template)
    {
        // Implement code to extract block references
        // Use regular expressions to find and collect block references
        preg_match_all('/{{#([^{}]+)}}(.*?){{\/\1}}/s', $template, $matches, PREG_SET_ORDER);
        return $matches;
    }

    private static function parseSingleReferences($template, $singleReferences, $data)
{
    foreach ($singleReferences as $reference) {
        $trimmedReference = trim($reference);
        // Replace the single reference with its corresponding data
        $placeholder = '{{ ' . $trimmedReference . ' }}';

        if (isset($data[$trimmedReference])) {
            $template = str_replace($placeholder, $data[$trimmedReference], $template);
        } else {
            // Handle error reporting for missing data
            // You can throw an exception, log an error, or take other actions
        }
    }

    return $template;
}


    private static function parseBlockReferences($template, $blockReferences, $data)
    {
        // Implement code to parse and replace block references
        foreach ($blockReferences as $match) {
            $blockName = $match[1];
            $blockContent = $match[2];
            // Call the BlockReferenceParser class to handle block references
            $blockContent = BlockReferenceParser::parse($blockContent, $blockName, $data); 
            // Replace the block reference with the parsed content
            $placeholder = '{{#' . $blockName . '}}' . $blockContent . '{{/' . $blockName . '}}';
            $template = str_replace($match[0], $placeholder, $template);
        }
        return $template;
    }
}
