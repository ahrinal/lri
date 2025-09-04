<?php

function getCroppedFile($filename){
    $exp = explode(".",$filename);

    return $exp[0].'-cropped.'.$exp[1];

}

function tanggal($date){
    return date('d-m-Y', strtotime($date));
}

function indoDate($datetime){
    $bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
    $date = explode("-",date("d-m-Y",strtotime($datetime)));
    $newdate = $date[0]." ".$bulan[$date[1]-1]." ".$date[2];
    return $newdate;
}

function linkYoutube($link){
    $exp = explode("=", $link);
    return end($exp);
}