<?php

return [
    'ticket' => [
        'category' => [
            ['id' => 1, 'name' => 'Access'],
            ['id' => 2, 'name' => 'Application'],
            ['id' => 3, 'name' => 'Network'],
            ['id' => 4, 'name' => 'Hardware'],
            ['id' => 5, 'name' => 'Security Incident'],
            ['id' => 6, 'name' => 'Telephony'],
        ],
        'priority' => [
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Medium'],
            ['id' => 3, 'name' => 'High'],
            ['id' => 4, 'name' => 'Urgent'],
            ['id' => 5, 'name' => 'Critical'],
        ],
        'resolution' => [
            ['id' => 1, 'name' => 'Permanent Fix'],
            ['id' => 2, 'name' => 'Workaround'],
            ['id' => 3, 'name' => 'Resolved by Caller'],
        ],
        'status' => [
            ['id' => 1, 'name' => 'Open'],
            ['id' => 2, 'name' => 'Pending'],
            ['id' => 3, 'name' => 'Resolved'],
            ['id' => 4, 'name' => 'Closed'],
            ['id' => 5, 'name' => 'Waiting for Agent'],
            ['id' => 6, 'name' => 'Waiting for Client'],
        ]
    ]
];
