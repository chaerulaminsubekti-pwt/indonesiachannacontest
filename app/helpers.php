<?php

if (! function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        $allowed = '<p><br><strong><b><em><i><u><a><ul><ol><li><span><blockquote><h1><h2><h3><h4><h5><h6><hr>';

        $html = strip_tags($html, $allowed);

        $html = preg_replace('/<\?(?:php)?\s.*?\?>/is', '', $html);

        $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        $html = preg_replace('/href\s*=\s*"(?!(?:https?:\/\/|mailto:|tel:|\/|#))[^"]*"/i', 'href="#"', $html);
        $html = preg_replace("/href\s*=\s*'(?!(?:https?:\/\/|mailto:|tel:|\/|#))[^']*'/i", "href='#'", $html);

        return $html;
    }
}
