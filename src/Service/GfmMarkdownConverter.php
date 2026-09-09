<?php

namespace App\Service;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Twig\Extra\Markdown\MarkdownInterface;

class GfmMarkdownConverter implements MarkdownInterface
{
    private GithubFlavoredMarkdownConverter $converter;

    public function __construct()
    {
        $this->converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
    }

    public function convert(string $body): string
    {
        return $this->converter->convert($body)->getContent();
    }
}
