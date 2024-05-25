<?php

if (! function_exists('checkUserShift')) {
    function checkUserShift($user)
    {
        if (! Gate::allows('work', $user)) {
            return redirect()->route('dashboard');
        }
    }
}

if (! function_exists('limitAndCleanString')) {
    function limitAndCleanString($string, $limit = 100): string
    {
        $result = strip_tags(
            Str::of($string)
                ->limit($limit)
                ->replace('</li><li>', ', ')
                ->replace("</p>\n\n<p>", ', ')
                ->replace('<p><br></p>', ', ')
        );

        // Replace multiple consecutive occurrences of ', ' with a single ', '
        $result = preg_replace('/(, ){2,}/', ', ', $result);

        // Trim leading and trailing ', '
        $result = trim($result, ', ');

        return $result;
    }
}
