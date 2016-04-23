<?php

echo \yadjet\ztree\ZTree::widget([
    'id' => '__ztree__',
    'nodes' => $data,
    'settings' => [
        'check' => [
            'enable' => true
        ]
]]);
