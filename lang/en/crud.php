<?php

return [
    'common' => [
        'actions' => 'Actions',
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'new' => 'New',
        'cancel' => 'Cancel',
        'attach' => 'Attach',
        'detach' => 'Detach',
        'save' => 'Save',
        'delete' => 'Delete',
        'delete_selected' => 'Delete selected',
        'search' => 'Search...',
        'back' => 'Back to Index',
        'are_you_sure' => 'Are you sure?',
        'no_items_found' => 'No items found',
        'created' => 'Successfully created',
        'saved' => 'Saved successfully',
        'removed' => 'Successfully removed',
    ],

    'admins' => [
        'name' => 'Admins',
        'index_title' => 'Admins List',
        'new_title' => 'New Admin',
        'create_title' => 'Create Admin',
        'edit_title' => 'Edit Admin',
        'show_title' => 'Show Admin',
        'inputs' => [
            'user_id' => 'User',
        ],
    ],

    'clients' => [
        'name' => 'Clients',
        'index_title' => 'Clients List',
        'new_title' => 'New Client',
        'create_title' => 'Create Client',
        'edit_title' => 'Edit Client',
        'show_title' => 'Show Client',
        'inputs' => [
            'user_id' => 'User',
        ],
    ],

    'commandes' => [
        'name' => 'Commandes',
        'index_title' => 'Commandes List',
        'new_title' => 'New Commande',
        'create_title' => 'Create Commande',
        'edit_title' => 'Edit Commande',
        'show_title' => 'Show Commande',
        'inputs' => [
            'date' => 'Date',
            'time' => 'Time',
            'drink' => 'Drink',
            'dessert' => 'Dessert',
            'food' => 'Food',
            'client_id' => 'Client',
        ],
    ],

    'desserts' => [
        'name' => 'Desserts',
        'index_title' => 'Desserts List',
        'new_title' => 'New Dessert',
        'create_title' => 'Create Dessert',
        'edit_title' => 'Edit Dessert',
        'show_title' => 'Show Dessert',
        'inputs' => [
            'name' => 'Name',
            'image' => 'Image',
            'price' => 'Price',
            'menu_id' => 'Menu',
        ],
    ],

    'drinks' => [
        'name' => 'Drinks',
        'index_title' => 'Drinks List',
        'new_title' => 'New Drink',
        'create_title' => 'Create Drink',
        'edit_title' => 'Edit Drink',
        'show_title' => 'Show Drink',
        'inputs' => [
            'name' => 'Name',
            'image' => 'Image',
            'price' => 'Price',
            'menu_id' => 'Menu',
        ],
    ],

    'all_food' => [
        'name' => 'All Food',
        'index_title' => 'AllFood List',
        'new_title' => 'New Food',
        'create_title' => 'Create Food',
        'edit_title' => 'Edit Food',
        'show_title' => 'Show Food',
        'inputs' => [
            'name' => 'Name',
            'image' => 'Image',
            'price' => 'Price',
            'menu_id' => 'Menu',
        ],
    ],

    'menus' => [
        'name' => 'Menus',
        'index_title' => 'Menus List',
        'new_title' => 'New Menu',
        'create_title' => 'Create Menu',
        'edit_title' => 'Edit Menu',
        'show_title' => 'Show Menu',
        'inputs' => [
            'drink_list' => 'Drink List',
            'dessert_list' => 'Dessert List',
            'food_list' => 'Food List',
            'client_id' => 'Client',
        ],
    ],

    'paiements' => [
        'name' => 'Paiements',
        'index_title' => 'Paiements List',
        'new_title' => 'New Paiement',
        'create_title' => 'Create Paiement',
        'edit_title' => 'Edit Paiement',
        'show_title' => 'Show Paiement',
        'inputs' => [
            'price' => 'Price',
            'client_id' => 'Client',
            'print_pdf' => 'Print Pdf',
        ],
    ],

    'reservations' => [
        'name' => 'Reservations',
        'index_title' => 'Reservations List',
        'new_title' => 'New Reservation',
        'create_title' => 'Create Reservation',
        'edit_title' => 'Edit Reservation',
        'show_title' => 'Show Reservation',
        'inputs' => [
            'number_table' => 'Number Table',
            'date' => 'Date',
            'time' => 'Time',
            'number_place' => 'Number Place',
            'client_id' => 'Client',
        ],
    ],

    'users' => [
        'name' => 'Users',
        'index_title' => 'Users List',
        'new_title' => 'New User',
        'create_title' => 'Create User',
        'edit_title' => 'Edit User',
        'show_title' => 'Show User',
        'inputs' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'nickname' => 'Nickname',
            'address' => 'Address',
            'phone' => 'Phone',
        ],
    ],

    'roles' => [
        'name' => 'Roles',
        'index_title' => 'Roles List',
        'create_title' => 'Create Role',
        'edit_title' => 'Edit Role',
        'show_title' => 'Show Role',
        'inputs' => [
            'name' => 'Name',
        ],
    ],

    'permissions' => [
        'name' => 'Permissions',
        'index_title' => 'Permissions List',
        'create_title' => 'Create Permission',
        'edit_title' => 'Edit Permission',
        'show_title' => 'Show Permission',
        'inputs' => [
            'name' => 'Name',
        ],
    ],
];
