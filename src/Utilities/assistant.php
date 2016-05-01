<?php
namespace AwatBayazidi\Abzar\Utilities;


class assistant
{

    const REGEX_EXTERNAL_URL = '/^((https?:)\/\/|data:)/i';

    /**
     * Convert an array of attributes to HTML.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function convertAttributesToHtml(array $attributes) {
        $html = '';
        if (count($attributes) > 0) {
            foreach ($attributes as $key => $value) {
                if (is_int($key)) {
                    $html .= ' ' . $value;
                } else {
                    $html .= ' ' . $key . '="' . $value . '"';
                }
            }
        }

        return $html;
    }

    /**
     * Is a URL absolute or relative?
     *
     * @return bool
     */
    public function isAbsoluteUrl($url) {
        return preg_match(self::REGEX_EXTERNAL_URL, $url) === 1;
    }

    /**
     * Normalize a path, removing '..' folders.
     *
     * e.g. "a/b/c/../../d" becomes "a/d"
     *
     * @param string $url
     *
     * @return string
     */
    public function normalizePath($url) {
        while (strpos($url, '/../') !== false) {
            $url = preg_replace('/[^\/]+\/\.\.\//', '', $url, 1);
        }

        return $url;
    }

    /**
     * Create a relative path between two URLs.
     *
     * e.g. the relative path from "a/b/c" to "a/d" is "../../d"
     *
     * @param string $url
     *
     * @return string
     */
    public function relativePath($source, $destination) {
        if ($source === '') {
            return $destination;
        }

        $parts1 = explode('/', $source);
        $parts2 = explode('/', $destination);

        while (!empty($parts1) && !empty($parts2) && $parts1[0] === $parts2[0]) {
            array_shift($parts1);
            array_shift($parts2);
        }

        return str_repeat('../', count($parts1)) . implode('/', $parts2);
    }



}