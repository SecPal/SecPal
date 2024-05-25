<?php

if (! function_exists('checkUserShift')) {
    function checkUserShift($user)
    {
        if (! Gate::allows('work', $user)) {
            return redirect()->route('dashboard');
        }
    }
}
