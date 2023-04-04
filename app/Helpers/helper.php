<?php

function format_rupiah($num){
    $rupiah = "Rp " . number_format($num,0,',','.');
    return $rupiah;
}

?>