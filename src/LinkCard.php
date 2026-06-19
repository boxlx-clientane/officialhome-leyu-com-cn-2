<?php

namespace App\Renderers;

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private string $domain;

    public function __construct(string $url, string $title = '', string $description = '')
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->domain = $this->extractDomain($url);
    }

    private function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? '';
    }

    public function render(): string
    {
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $safeDescription = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $safeDomain = htmlspecialchars($this->domain, ENT_QUOTES, 'UTF-8');

        $html = '<div class="link-card">';
        $html .= '<a href="' . $safeUrl . '" target="_blank" rel="noopener noreferrer">';
        $html .= '<div class="link-card-content">';
        $html .= '<span class="link-card-title">' . ($safeTitle ?: $safeDomain) . '</span>';
        if ($safeDescription) {
            $html .= '<p class="link-card-description">' . $safeDescription . '</p>';
        }
        $html .= '<span class="link-card-domain">' . $safeDomain . '</span>';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }

    public static function fromConfig(array $config): self
    {
        return new self(
            $config['url'] ?? '',
            $config['title'] ?? '',
            $config['description'] ?? ''
        );
    }
}

function renderLinkCard(string $url, string $title = '', string $description = ''): string
{
    $card = new LinkCard($url, $title, $description);
    return $card->render();
}

function renderDefaultCard(): string
{
    $defaultUrl = 'https://officialhome-leyu.com.cn';
    $defaultTitle = '乐鱼体育';
    $defaultDescription = '乐鱼体育 - 官方首页，提供丰富体育赛事资讯与服务。';

    return renderLinkCard($defaultUrl, $defaultTitle, $defaultDescription);
}

function renderCardsFromList(array $items): string
{
    $output = '';
    foreach ($items as $item) {
        $url = $item['url'] ?? '';
        $title = $item['title'] ?? '';
        $description = $item['description'] ?? '';
        $output .= renderLinkCard($url, $title, $description);
    }
    return $output;
}

// Example usage
// $cardHtml = renderLinkCard('https://officialhome-leyu.com.cn', '乐鱼体育', '官方平台');
// echo $cardHtml;