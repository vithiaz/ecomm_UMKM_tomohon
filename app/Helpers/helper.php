<?php

function format_rupiah($num){
    $rupiah = "Rp " . number_format($num,0,',','.');
    return $rupiah;
}

function simplify_number_id($number) {
    if ($number >= 1000) {
        return number_format($number / 1000, 1) . 'rb';
    }
    return $number;
}


?>