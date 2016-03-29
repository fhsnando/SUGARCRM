<?php

$key_c="SKRO-35";
$description_c="SKRO-35";
$author_c="Fernando Henrique";
$date_c=date('Y/m/d');
$version_c=rand();

$manifest = array(
    'build_in_version'=>'7.6.1.0',
    'acceptable_sugar_versions' =>
        array (
            'regex_matches' => array(
                0 => '7\.*'
            ),
        ),
    'acceptable_sugar_flavors' =>
        array (
            0 => 'ENT',
            1 => 'ULT',
            2 => 'PRO',
        ),
    'readme' => "",
    'key' => "$key_c",
    'name' => "$key_c",
    'author' => "$author_c",
    'description' => "$description_c",
    'icon' => '',
    'is_uninstallable' => true,
    'published_date' => "$date_c",
    'type' => 'module',
    'version' => "$version_c",
    'remove_tables' => 'prompt',
);

$installdefs = array (
    'id' => "$key_c,$version_c",
    'mkdir' => array(
        array('custom/include/'),
        array('custom/include/javascript/'),
    ),
    'copy' =>
    array (
        array (
            'from' => '<basepath>/clients',
            'to' => 'custom/clients',
        ),
        array (
            'from' => '<basepath>/Extension',
            'to' => 'custom/Extension',
        ),
        array (
            'from' => '<basepath>/include',
            'to' => 'custom/include',
        ),
    ),
);