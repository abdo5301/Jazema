<?php

return [


    [
        'name' => __('permission-groups Permissions'),
        'description' => __('permission-group Permissions Description'),
        'permissions' => [
            'view-all-permission-groups' => ['system.permission-group.index'],
            'view-one-permission-groups' => ['system.permission-group.show'],
            'delete-one-permission-groups' => ['system.permission-group.destroy'],
            'create-permission-groups' => ['system.permission-group.create', 'system.permission-group.store'],
            'update-permission-groups' => ['system.permission-group.edit', 'system.permission-group.update'],
        ]
    ],


    /*
  * Deals
  */
    [
        'name' => __('Deals Permissions'),
        'description' => __('Deals permissions Description'),
        'permissions' => [
            'view-all-deals' => ['system.deal.index'],
            'view-one-deal' => ['system.deal.show'],
            'delete-one-deal' => ['system.deal.destroy'],
            'create-deal' => ['system.deal.create', 'system.deal.store'],
            'update-deal' => ['system.deal.edit', 'system.deal.update'],
        ]
    ],

    /*
    * users
    */
    [
        'name' => __('Users Permissions'),
        'description' => __('Users permissions Description'),
        'permissions' => [
            'view-all-users' => ['system.users.index'],
            'view-one-user' => ['system.users.show'],
            'delete-one-user' => ['system.users.destroy'],
            'create-user' => ['system.users.create', 'system.users.store'],
            'update-user' => ['system.users.edit', 'system.users.update'],
        ]
    ],
    //Template Options
    [
        'name' => __('Template Options'),
        'description' => __('Template Options permissions Description'),
        'permissions' => [
            'view-all-options' => ['system.option.index'],
            'view-one-option' => ['system.option.show'],
            'delete-one-option' => ['system.option.destroy'],
            'create-option' => ['system.option.create', 'system.option.store'],
            'update-option' => ['system.option.edit', 'system.option.update'],
        ]
    ],
    
/*
     * Items
     */
    [
        'name' => __('Items Permissions'),
        'description' => __('Items permissions Description'),
        'permissions' => [
            'view-all-items' => ['system.item.index'],
            'view-one-item' => ['system.item.show'],
            'delete-one-item' => ['system.item.destroy'],
            'create-item' => ['system.item.create', 'system.item.store'],
            'update-item' => ['system.item.edit', 'system.item.update'],
        ]
    ],

    /*
    * staff
    */
    [
        'name' => __('Staff Permissions'),
        'description' => __('Staff Permissions Description'),
        'permissions' => [
            'view-all-staff' => ['system.staff.index'],
            'view-one-staff' => ['system.staff.show'],
            'delete-one-staff' => ['system.staff.destroy'],
            'create-staff' => ['system.staff.create', 'system.staff.store'],
            'update-staff' => ['system.staff.edit', 'system.staff.update'],
            'add-managed-staff' => ['system.staff.add-managed-staff'],
            'delete-managed-staff' => ['system.staff.delete-managed-staff'],
            'show-tree-users-data' => ['show-tree-users-data'],
        ]
    ],


    /*
    * area-types
    */
    [
        'name' => __('Area types Permissions'),
        'description' => __('Area types Permissions Description'),
        'permissions' => [
            'view-all-area-type' => ['system.area-type.index'],
            'view-one-area-type' => ['system.area-type.show'],
            'delete-one-area-type' => ['system.area-type.destroy'],
            'create-area-type' => ['system.area-type.create', 'system.area-type.store'],
            'update-area-type' => ['system.area-type.edit', 'system.area-type.update'],
        ]
    ],


    /*
    * areas
    */
    [
        'name' => __('invoice Permissions'),
        'description' => __('Area Permissions Description'),
        'permissions' => [
            'view-all-areas' => ['system.area.index'],
            'view-one-area' => ['system.area.show'],
            'delete-one-area' => ['system.area.destroy'],
            'create-area' => ['system.area.create', 'system.area.store'],
            'update-area' => ['system.area.edit', 'system.area.update'],
        ]
    ],

    /*
    * activity-log
    */
    [
        'name' => __('System activity log Permissions'),
        'description' => __('System activity log Permissions Description'),
        'permissions' => [
            'view-activity-log' => ['system.activity-log.show'],
            'view-merchant-staff-log' => ['merchant.staff-log'],
            'view-staff-sales-log' => ['staff.staff-sales-log'],
        ]
    ],


    /*
    * System Setting
    */
    [
        'name' => __('System Permissions'),
        'description' => __('System settings Permissions Description'),
        'permissions' => [
            'encrypt-or-decrypt-data' => ['system.encrypt'],
            'system-settings' => ['system.setting.index', 'system.setting.update'],
            'activity-log' => ['system.activity-log.index', 'system.activity-log.show']
        ]
    ],

    [
        'name' => __('Contact Us Permissions'),
        'description' => __('Contact Us Permissions Description'),
        'permissions' => [
            'view-all-contact-us' => ['system.contact-us.index'],
            'view-one-contact-us' => ['system.contact-us.show'],
            'delete-one-contact-us' => ['system.contact-us.destroy']
        ]
    ],


    [
        'name' => __('Item Types Permissions'),
        'description' => __('Item Types Permissions Description'),
        'permissions' => [
            'view-all-item-types' => ['system.item_type.index'],
            'view-one-item-type' => ['system.item_type.show'],
            'create-item-type' => ['system.item_type.create', 'system.item_type.store'],
            'update-item-type' => ['system.item_type.edit', 'system.item_type.update'],
            'delete-one-item-types' => ['system.item_type.destroy']
        ]
    ],
    [
        'name' => __('Item Categories Permissions'),
        'description' => __('Item Types Permissions Description'),
        'permissions' => [
            'view-all-item-categories' => ['system.item_category.index'],
            'view-one-item-category' => ['system.item_category.show'],
            'create-item-category' => ['system.item_category.create', 'system.item_category.store'],
            'update-item-category' => ['system.item_category.edit', 'system.item_category.update'],
            'delete-one-item-categories' => ['system.item_category.destroy']
        ]
    ],
    [
        'name' => __('User Jobs Permissions'),
        'description' => __('User Jobs Permissions Description'),
        'permissions' => [
            'view-all-user-jobs' => ['system.job.index'],
           // 'view-one-item-category' => ['system.item_category.show'],
            'create-user-job' => ['system.job.create', 'system.job.store'],
            'update-user-job' => ['system.job.edit', 'system.job.update'],
            'delete-one-user-job' => ['system.job.destroy']
        ]
    ],

    [
        'name' => __('Attributes Permissions'),
        'description' => __('Attributes Permissions Description'),
        'permissions' => [
            'view-all-attributes' => ['system.attributes.index'],
             'view-one-attribute' => ['system.attributes.show'],
            'create-attribute' => ['system.attributes.create', 'system.attributes.store'],
            'update-attribute' => ['system.attributes.edit', 'system.attributes.update'],
            'delete-attribute' => ['system.attributes.destroy']
        ]
    ],
    /*
    * Chat
    */
    [
        'name' => __('system.chat.index Permissions'),
        'description' => __('Chat Permissions Description'),
        'permissions' => [
            'system-chat' => ['system.chat.index', 'system.chat.get-conversation'],
        ]
    ],

//    [
//        'name' => __('Access data.index Permissions'),
//        'description' => __('Access data Permissions Description'),
//        'permissions' => [
//            'system-access-data-index'=>['system.access-data.index'],
//            'view-all-audio-messages'=>['system.audio-messages.index'],
//            'view-one-audio-messages'=>['system.audio-messages.show'],
//            'view-dashboard-analytics'=> ['view-dashboard-analytics']
//        ]
//    ],

    [
        'name' => __('Pages Permissions'),
        'description' => __('Pages Permissions Description'),
        'permissions' => [
            'view-all-Pages' => ['system.pages.index'],
            'create-Page' => ['system.pages.create', 'system.pages.store'],
            'update-Page' => ['system.pages.edit', 'system.pages.update'],
            'delete-Page' => ['system.pages.destroy']
        ]
    ],

    [
        'name' => __('Services Permissions'),
        'description' => __('Services Permissions Description'),
        'permissions' => [
            'view-all-Services' => ['system.services.index'],
            'create-Service' => ['system.services.create', 'system.services.store'],
            'update-Service' => ['system.services.edit', 'system.services.update'],
            'delete-Service' => ['system.services.destroy']
        ]
    ],
];