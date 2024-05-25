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
        return strip_tags(
            Str::of($string)
                ->limit($limit)
                ->replace('</li><li>', ', ')
                ->replace("</p>\n\n<p>", ', ')
        );
    }
}
