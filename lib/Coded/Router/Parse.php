<?php

namespace Coded\Router;

class Parse
{
    private $get = [];

    function __construct($url, int $skip = 0)
    {
        $key = 0;
        $this->get = [];
        $data = parse_url($url);
        $base = explode('/', trim($data['path'], '/'));
        foreach ($base as $key => $value) {
            if (!strlen($value)) continue;
            if (($newKey = ($key - $skip)) < 0) continue;

            $this->get[$newKey] = (strpos($value, '=') !== false) ? static::parse($value) : urldecode($value);
        }

        if (isset($data['query'])) {
            $newKey = $key + 1 - $skip;
            $this->get[$newKey > 0 ? $newKey : 0] = static::parse($data['query']);
        }
    }

    function get(int $n = null)
    {
        return $this->get[$n] ?? $this->get;
    }

    private static function parse($string)
    {
        $out = [];
        parse_str($string, $out);
        foreach ($out as &$v) {
            $v = urldecode($v);
        }
        return $out;
    }

    static function url()
    {
        $url = defined('PARSE_URL') ? PARSE_URL : $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $args = [];
        $input = array_filter(func_get_args());
        if (!$input) return trim($url, '/');
        foreach (func_get_args() as $part) {
            if (is_array($part)) $args[] = http_build_query($part);
            elseif (is_object($part)) $args[] = get_class($part);
            else $args[] = $part;
        }
        $href = implode('/', array_filter($args));

        $href = str_replace($url . '/', '', $href);

        if ($href == false)
            $href = $_SERVER['REQUEST_URI'];
        elseif ($href == $url)
            $href = '';
        else
            $href = '/' . trim($href, '/');

        return trim(strtolower($url . $href), '/');
    }
}