<?php
namespace App\Helpers;
$action = [
    'remove'     => 'REMOVE',
    'approve'    => 'APPROVE', 
    'disapprove' => 'DISAPPROVE',   
    'viewed'     => 'VIEWED',    
    'create'     => 'CREATED',
    'update'     => 'UPDATED'
];
$importStatus = [
    'SUCCESS' => 1,
    'FAIL' => 2,
    'IMPORTING' => 3,
    'DELETED' => 4,
];
define('ACTION', $action);
define('IMPORT_STATUS', $importStatus);