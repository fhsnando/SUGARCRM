<?php
/**
 * Created by PhpStorm.
 * User: Fernando Santos
 */

$key_c="Rep_dash_ondemand";
$description_c="Update base de dados - adicionar dashboards";
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
    'readme' => "readme.txt",
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
    'pre_execute' => array(    0 =>  '<basepath>/atualizar.php',),
//        'copy' =>
//                array (
//                        array (
//                                'from' => '<basepath>/Extension',
//                                'to' => 'custom/Extension',
//                        ),
//                        array (
//                                'from' => '<basepath>/modules',
//                                'to' => 'custom/modules',
//                        ),
//                ),
);