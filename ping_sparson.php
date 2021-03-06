<?php
include("common.php");
// CRONTAB ENTRY: */5 * * * * root php /home/sparson/sparson/ping_sparson.php

$hostname = urlencode(gethostname());
$uname	  = urlencode(php_uname());
$os       = urlencode(PHP_OS);
$distro_i       = get_distro();
if(isset($distro_i["DISTRIB_ID"])) {
$distro         = urlencode($distro_i["DISTRIB_ID"]);
$distroversion  = urlencode($distro_i["DISTRIB_RELEASE"]);
$distrocodename = urlencode($distro_i["DISTRIB_CODENAME"]);
}
else {
    $distro=$distro_i["PRETTY_NAME"];
    $distroex=explode(" ",$distro);
    $distro=urlencode($distroex[0]);
    $distroversion=urlencode($distroex[2]);
    $distrocodename=$distroex[3];
    $distrocodename=str_replace("(","",$distrocodename);
    $distrocodename=str_replace(")","",$distrocodename);
    $distrocodename=urlencode($distrocodename);
}

$url="http://sparson.com?a=p";
$url.="&hostname=$hostname";
$url.="&os=$os";
$url.="&distro=$distro";
$url.="&distroversion=$distroversion";
$url.="&distrocodename=$distrocodename";

$drives=urlencode(get_drives());
$url.="&drives=$drives";

////////////////////////////////////////////////////////////////////////////////////////////////////////
// Check for update
if(gethostname()!="area56.sethcoder.com") {
    $current_time=time();
    $last_update_check=file_get_contents("update_check");
    if(  $last_update_check < 0 || 
        ($current_time - $last_update_check) > 600) {
        $dir = "/home/sparson/sparson";
        file_put_contents("update_check",$current_time);
        exec("cd $dir;git pull",$r);
        if(count($r)>1) {
            $o="";
            foreach($r as $k => $v) {
                 $o.=date("Y-m-d H:i:s").": ".$v."\n";
            }
            $f=fopen("update.log","a");
            fputs($f,$o);
            fclose($f);
        }
    }
}

$x=file_get_contents($url);

