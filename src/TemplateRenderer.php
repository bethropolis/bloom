<?php 
namespace Bethropolis\Bloom;

/*
a php templating library

*/

use Bethropolis\Bloom\Template\Load;
use Bethropolis\Bloom\Template\TemplateParser;

class TemplateRenderer
{
    private string $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function render(array $data): string
    {
        $output = Load::fetch($this->template);
        $output = TemplateParser::parseTemplate($output, $data);

        return $output;
    }

}
