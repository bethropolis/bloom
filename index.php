<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bethropolis\Bloom\TemplateRenderer;
use Bethropolis\Bloom\Template\Path;

$path = new Path(__DIR__ . '/example');

$renderer = new TemplateRenderer("template.bloom");

$data = [
    'title' => 'My Page',
    'heading' => 'Welcome to My Website',
    'link1' => 'https://example.com',
    'link1Text' => 'Example',
    'link2' => 'https://openai.com',
    'link2Text' => 'OpenAI',
    'username' => 'Bethuel',
    'content' => 'This is the content of my page.',
];

$output = $renderer->render($data);
echo $output;