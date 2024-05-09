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
$equipmentPosition = [
    ['value' => 1, 'name' => 'Vũ Khí'],
    ['value' => 2, 'name' => 'Mũ'],
    ['value' => 3, 'name' => 'Phi Phong'],
    ['value' => 4, 'name' => 'Y Phục'],
    ['value' => 5, 'name' => 'Giày'],
    ['value' => 6, 'name' => 'Huy Hiệu']
];
$typeMarket = [
    'course'     => 'course',
    'equipment'   => 'equipment',
];
// status quest
$statusQuest = [
    'pending'  => '01',
    'received' => '02',
];
define('ACTION', $action);
define('EQUIPMENT_POSITION', $equipmentPosition);
define('TYPE_MARKET', $typeMarket);
define('STATUS_QUEST', $statusQuest);