<?php

namespace PEAR2Web;

class Router
{
    public static function getRoute($requestURI)
    {
        $options = array();

        if (!empty($_SERVER['QUERY_STRING'])) {
            $requestURI = substr(
                $requestURI,
                0,
                -strlen($_SERVER['QUERY_STRING']) - 1
            );
        }

        $base       = \PEAR2\SimpleChannelFrontend\Main::getURL();
        $quotedBase = preg_quote($base, '/');
        $packageExp = "/"
            . "^"
            . $quotedBase . "            # base"
            . "(?<package>[0-9a-z_]+)    # package name"
            . "(-                        # dash separator"
            . "    (?<version>[0-9ab.]+) # version"
            . ")?                        # ... is optional"
            . "$"
            . "/xi";

        if ($requestURI === $base) {
            $options['view'] = 'news';
        } elseif ($requestURI === $base . 'categories/') {
            $options['view'] = 'categories';
        } elseif ($requestURI === $base . 'packages/') {
            $options['view'] = 'packages';
        } elseif ($requestURI === $base . 'support/') {
            $options['view'] = 'support';
        } elseif (preg_match($packageExp, $requestURI, $matches) === 1) {

            // Viewing an individual package
            $options['view']    = 'package';
            $options['package'] = $matches['package'];

            // Release selected
            if (isset($matches['version'])) {
                $options['view']           = 'release';
                $options['packageVersion'] = $matches['version'];
            }

        }

        return $options;
    }
}