<?php
function edit_selected($array, $array_value, $option_value)
{
    if (!$array) {
        return '';
    }
    if ($array_value == $option_value) {
        return 'selected';
    }
    return '';
}

function edit_value($array, $index)
{
    if (!$array) {
        return '';
    }
    if (isset($array[$index])) {
        return $array[$index];
    }
    return '';
}
