<?php
function validateURL($URL) {
    $pattern = "/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/";
    if(preg_match($pattern, $URL)){
        return true;
    } else{
        return false;
    }
}