<?php

namespace App\Services;

class AppCensurator
{
public function purify($subject)
{
    $motsInterdits=array("pute","salope","enculer","connard");
    $search=$motsInterdits;

    $replace="****";
   $text= str_ireplace($search,$replace,$subject);
   return $text;
}
}