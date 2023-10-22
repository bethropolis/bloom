<?php

namespace Bethropolis\Bloom\Template;


class BlockReferenceParser
{
    public static function parse($template, $reference, $data)
    {
        list($action, $content) = self::parseAction($reference);

        if (strtolower($action) === 'if') {
            $template = self::parseIfStatement($content, $data, $template);
        } elseif (strtolower($action) === 'loop') {
            $template = self::parseLoop($content, $data, $template);
        } else {
            // Handle custom actions (if needed)
        }

        return $template;
    }

    private static function parseAction($reference)
    {
        // Separates the action and content from the reference
        preg_match('/^([a-zA-Z]+)\s+(.*)$/', $reference, $matches);
        $action = $matches[1];
        $content = $matches[2];

        return [$action, $content];
    }

    private static function parseIfStatement($content, $data, $template)
    {
        // Parses if statements
        preg_match('/\{\{\s*if\s+(.+?)\s*\}\}(.*?)\{\{\s*\/if\s*\}\}/s', $template, $matches);
        $condition = $matches[1];
        $ifContent = $matches[2];

        if (self::evaluateCondition($condition, $data)) {
            // Condition is true, parse the content within the if block
            $template = str_replace($matches[0], $ifContent, $template);
        } else {
            // Condition is false, remove the if block
            $template = str_replace($matches[0], '', $template);
        }

        return $template;
    }

    private static function parseLoop($content, $data, $template)
    {
        // Parses loop blocks
        preg_match('/\{\{\s*loop\s+(.+?)\s*as\s+(\w+)\s*\}\}(.*?)\{\{\s*\/loop\s*\}\}/s', $template, $matches);
        $arrayKey = $matches[1];
        $itemKey = $matches[2];
        $loopContent = $matches[3];

        if (isset($data[$arrayKey]) && is_array($data[$arrayKey])) {
            // Loop over the array and replace the loop block with each iteration
            $loopOutput = '';
            foreach ($data[$arrayKey] as $item) {
                $itemData = [$itemKey => $item];
                $loopOutput .= self::parseTemplate($loopContent, $itemData);
            }

            $template = str_replace($matches[0], $loopOutput, $template);
        } else {
            // Data is missing or not an array, remove the loop block
            $template = str_replace($matches[0], '', $template);
        }

        return $template;
    }

    private static function parseTemplate($template, $data)
    {
        // Parse the template with the data
        foreach ($data as $key => $value) {
            $placeholder = '{{ ' . $key . ' }}';
            $template = str_replace($placeholder, $value, $template);
        }
    }

    private static function evaluateCondition($condition, $data)
    {
        // Implement code to evaluate if statement conditions
        // You can use a simple evaluation, like checking if a data key exists
        return isset($data[$condition]);
    }
}
