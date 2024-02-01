<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [ // Administrador
            'users' => 'c,r,u,d',
            'profile' => 'c,r,u,d',
            'notices' => 'c,r,u,d',
            'posts' => 'c,r,u,d'
        ],
        'employee' => [ // Empleado
            'profile' => 'r,u',
            'notices' => 'r',
            'posts' => 'c,r,u,d',
        ],
    ],
    'permission_structure' => [
        'cru_user' => [
            'profile' => 'c,r,u'
        ],
    ],
    'permissions_map' => [
        'n' => 'navigate',
        'a' => 'authorize',
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];