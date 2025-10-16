<?php
// Minimal PHP 8 compatibility shims for legacy mysql_* APIs used in this project.
// These wrap mysqli so we don't have to touch all call sites. Keep behavior as close as possible.

if (!function_exists('mysql_connect')) {
    function mysql_connect($host = null, $user = null, $password = null)
    {
        $link = mysqli_connect($host, $user, $password);
        if (!$link) {
            die('Cannot connect to Mysql server host');
        }
        return $link;
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($dbname, $link_identifier = null)
    {
        return mysqli_select_db($link_identifier, $dbname);
    }
}

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link_identifier = null)
    {
        return mysqli_query($link_identifier, $query);
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result)
    {
        return mysqli_fetch_array($result, MYSQLI_BOTH);
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link_identifier = null)
    {
        return mysqli_error($link_identifier);
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($unescaped_string, $link_identifier = null)
    {
        if ($link_identifier) {
            return mysqli_real_escape_string($link_identifier, $unescaped_string);
        }
        return addslashes($unescaped_string);
    }
}
