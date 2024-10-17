@php


    $menu['Dashboard'] = [
             'url'=> route('system.dashboard'),
             'icon'=>'ft-home',
             'text'=>__('Dashboard'),
      ];

    $menu['Chat'] = [
             'permission'=> [
                  'system.chat.index'
             ],
             'class'=>'',
             'icon'=>'fa fa-commenting-o',
             'text'=>__('Chat'),
             'url'=> route('system.chat.index'),

         ];

    $menu['Deals'] = [
             'permission'=> [
                  'system.deal.index'
             ],
             'class'=>'',
             'icon'=>'fa fa-commenting-o',
             'text'=>__('Deals'),
             'url'=> route('system.deal.index'),

         ];


      $menu['Staff'] = [
             'permission'=> [
                  'system.staff.index',
                  'system.staff.create',
                  'system.staff.edit'
                  ],
             'class'=>'',
             'icon'=>'fa fa-user',
             'text'=>__('Staff'),
             'sub'=>[



                 'Staff'=> [
                     'permission'=> [
                         'system.staff.index',
                         'system.staff.create'
                     ],
                     'icon'=>'fa fa-user',
                     'text'=> __('Staff'),
                     'sub'=>[
                      'User Addresses'=> [
                     'permission'=> [
                         'system.address.index',
                         'system.address.create',
                         'system.address.edit'
                     ],
                     'icon'=>'fa fa-address-book',
                     'text'=> __('User Addresses'),
                     'sub'=>[
                         [
                             'permission'=> 'system.address.index',
                             'url'=> route('system.address.index'),
                             'text'=> __('View User Addresses')
                         ],

                         [
                             'aClass'=> 'color-red',
                             'permission'=> 'system.address.index',
                             'url'=> route('system.address.index',['withTrashed'=>1]),
                             'text'=> __('Trashed User Addresses')
                         ],

                     ]
                 ],
                         [
                             'permission'=> 'system.staff.index',
                             'url'=> route('system.staff.index'),
                             'text'=> __('View Staff')
                         ],
                         [
                             'permission'=> 'system.staff.create',
                             'url'=> route('system.staff.create'),
                             'text'=> __('Create Staff')
                         ],
                         [
                             'aClass'=> 'color-red',
                             'permission'=> 'system.staff.index',
                             'url'=> route('system.staff.index',['withTrashed'=>1]),
                             'text'=> __('Trashed Staff')
                         ],

                     ]
                 ],

                 'Permission'=> [
                     'permission'=> [
                         'system.permission-group.index',
                         'system.permission-group.create',
                         'system.permission-group.edit'
                     ],
                     'icon'=>'fa fa-universal-access',
                     'text'=> __('Permissions'),
                     'sub'=>[
                         [
                             'permission'=> 'system.permission-group.index',
                             'url'=> route('system.permission-group.index'),
                             'text'=> __('View Permissions')
                         ],
                         [
                             'permission'=> 'system.permission-group.create',
                             'url'=> route('system.permission-group.create'),
                             'text'=> __('Create Permission')
                         ],
                         [
                             'aClass'=> 'color-red',
                             'permission'=> 'system.permission-group.index',
                             'url'=> route('system.permission-group.index',['withTrashed'=>1]),
                             'text'=> __('Trashed Permissions')
                         ],

                     ]
                 ],


             ]
         ];

      $menu['Users'] = [
             'permission'=> [
                  'system.users.index',
                  'system.users.create',
                  'system.users.edit',
                  ],
             'class'=>'',
             'icon'=>'fa fa-users',
             'text'=>__('Users'),
             'sub'=>[
              'Jobs'=> [
              'permission'=> [
              'system.job.index',
              'system.job.create',
              'system.job.edit',
              ],
              'icon'=>'fa fa-map-marker',
              'text'=> __('Job'),
              'sub'=>[
              [
              'permission'=> 'system.job.index',
              'url'=> route('system.job.index'),
              'text'=> __('View Jobs')
              ],

              [
              'permission'=> 'system.job.create',
              'url'=> route('system.job.create'),
              'text'=> __('Create Job')
              ],
                [
              'permission'=> 'system.attributes.index',
              'url'=> route('system.attributes.index',['attr_type'=>'job']),
              'text'=> __('View  Job Attributes')
              ],
                [
              'permission'=> 'system.attributes.create',
              'url'=> route('system.attributes.create',['attr_type'=>'job']),
              'text'=> __('Create Job Attributes')
              ],
              [
              'aClass'=> 'color-red',
              'permission'=> 'system.job.index',
              'url'=> route('system.job.index',['withTrashed'=>1]),
              'text'=> __('Trashed Jobs')
              ],

              ]
              ],


                 'View'=> [
                     'permission'=> 'system.users.index',
                     'url'=> route('system.users.index'),
                     'text'=> __('View Users'),
                 ],

                 'Create'=> [
                     'permission'=> 'system.users.create',
                     'url'=> route('system.users.create'),
                     'text'=> __('Create User'),
                 ],

                 [
                     'aClass'=> 'color-red',
                     'permission'=> 'system.users.index',
                     'url'=> route('system.users.index',['withTrashed'=>1]),
                     'text'=> __('Trashed Users')
                 ],

             ]
         ];






          $menu['Items'] = [
          'permission'=> [
          'system.item_type.index',
          'system.item_type.create',
          'system.item_type.edit',
          ],
          'class'=>'',
          'icon'=>'fa fa-globe',
          'text'=>__('Items'),
          'sub'=>[
            'Items'=> [
            'permission'=> [
            'system.item.index',
            'system.item.create'
            ],
            'icon'=>'fa fa-compass',
            'text'=> __('Items'),
            'sub'=>[
            [
            'permission'=> 'system.item.index',
            'url'=> route('system.item.index'),
            'text'=> __('View Items')
            ],
            [
            'permission'=> 'system.item.create',
            'url'=> route('system.item.create'),
            'text'=> __('Create Item')
            ],
            [
            'aClass'=> 'color-red',
            'permission'=> 'system.item.index',
            'url'=> route('system.item.index',['withTrashed'=>1]),
            'text'=> __('Trashed Items')
            ],
            ]
            ],

          'Item Types'=> [
          'permission'=> [
          'system.item_type.index',
          'system.item_type.create'
          ],
          'icon'=>'fa fa-compass',
          'text'=> __('Item Types'),
          'sub'=>[
          [
          'permission'=> 'system.item_type.index',
          'url'=> route('system.item_type.index'),
          'text'=> __('View Item Types')
          ],
          [
          'permission'=> 'system.item_type.create',
          'url'=> route('system.item_type.create'),
          'text'=> __('Create Item Type')
          ],
          [
          'aClass'=> 'color-red',
          'permission'=> 'system.item_type.index',
          'url'=> route('system.item_type.index',['withTrashed'=>1]),
          'text'=> __('Trashed Item Types')
          ],

          ]
          ],
          'Item Categories'=> [
          'permission'=> [
          'system.item_category.index',
          'system.item_category.create',
          'system.item_category.edit',
          ],
          'icon'=>'fa fa-map-marker',
          'text'=> __('Item Categories'),
          'sub'=>[
          [
          'permission'=> 'system.item_category.index',
          'url'=> route('system.item_category.index'),
          'text'=> __('View Item Categories')
          ],
          [
          'permission'=> 'system.item_category.create',
          'url'=> route('system.item_category.create'),
          'text'=> __('Create Item Category')
          ],
          [
          'aClass'=> 'color-red',
          'permission'=> 'system.item_category.index',
          'url'=> route('system.item_category.index',['withTrashed'=>1]),
          'text'=> __('Trashed Item Categories')
          ],

          ]
          ],
  'Attributes'=> [
  'permission'=> [
  'system.attributes.index',
  'system.attributes.create',
  'system.attributes.edit',
  ],
  'icon'=>'fa fa-map-marker',
  'text'=> __('Attributes'),
  'sub'=>[

    [
  'permission'=> 'system.attributes.index',
  'url'=> route('system.attributes.index',['attr_type'=>'item']),
  'text'=> __('View Item Attributes')
  ],

  [
  'permission'=> 'system.attributes.create',
  'url'=> route('system.attributes.create',['attr_type'=>'item']),
  'text'=> __('Create Item Attributes')
  ],
  [
  'aClass'=> 'color-red',
  'permission'=> 'system.attributes.index',
  'url'=> route('system.attributes.index',['withTrashed'=>1]),
  'text'=> __('Trashed Item Attributes')
  ],

  ]
  ],
  'Items'=> [
  'permission'=> [
  'system.item.index',
  'system.item.create',
  'system.item.edit',
  ],
  'icon'=>'fa fa-map-marker',
  'text'=> __('Items'),
  'sub'=>[
  [
  'permission'=> 'system.item.index',
  'url'=> route('system.item.index'),
  'text'=> __('View Items')
  ],
  [
  'permission'=> 'system.item.create',
  'url'=> route('system.item.create'),
  'text'=> __('Create Item')
  ],
  [
  'aClass'=> 'color-red',
  'permission'=> 'system.item.index',
  'url'=> route('system.item.index',['withTrashed'=>1]),
  'text'=> __('Trashed Items')
  ],

  ]
  ],

          ]
          ];




  $menu['Template Options'] = [

  'permission'=> [
  'system.option.index',
  'system.option.create',
  'system.option.edit',
  ],
  'icon'=>'fa fa-map-marker',
  'text'=> __('Template Options'),
  'sub'=>[
  [
  'permission'=> 'system.option.index',
  'url'=> route('system.option.index'),
  'text'=> __('View Options')
  ],
  [
  'permission'=> 'system.option.create',
  'url'=> route('system.option.create'),
  'text'=> __('Create Option')
  ],

  [
  'aClass'=> 'color-red',
  'permission'=> 'system.option.index',
  'url'=> route('system.option.index',['withTrashed'=>1]),
  'text'=> __('Trashed Options')
  ],
  ]
  ];

  $menu['Location'] = [
             'permission'=> [
                      'system.area-type.index',
                      'system.area-type.create',
                      'system.area-type.edit',
                      ],
             'class'=>'',
             'icon'=>'fa fa-globe',
             'text'=>__('Location'),
             'sub'=>[

                 'AreaType'=> [
                     'permission'=> [
                         'system.area-type.index',
                         'system.area-type.create'
                     ],
                     'icon'=>'fa fa-compass',
                     'text'=> __('Area Types'),
                     'sub'=>[
                         [
                             'permission'=> 'system.area-type.index',
                             'url'=> route('system.area-type.index'),
                             'text'=> __('View Area Types')
                         ],
                         [
                             'permission'=> 'system.area-type.create',
                             'url'=> route('system.area-type.create'),
                             'text'=> __('Create Area Type')
                         ],
                         [
                             'aClass'=> 'color-red',
                             'permission'=> 'system.area-type.index',
                             'url'=> route('system.area-type.index',['withTrashed'=>1]),
                             'text'=> __('Trashed Area Types')
                         ],

                     ]
                 ],
                 'Areas'=> [
                     'permission'=> [
                         'system.area.index',
                         'system.area.create',
                         'system.area.edit',
                     ],
                     'icon'=>'fa fa-map-marker',
                     'text'=> __('Areas'),
                     'sub'=>[
                         [
                             'permission'=> 'system.area.index',
                             'url'=> route('system.area.index'),
                             'text'=> __('View Areas')
                         ],
                         [
                             'permission'=> 'system.area.create',
                             'url'=> route('system.area.create'),
                             'text'=> __('Create Area')
                         ],
                         [
                             'aClass'=> 'color-red',
                             'permission'=> 'system.area.index',
                             'url'=> route('system.area.index',['withTrashed'=>1]),
                             'text'=> __('Trashed Areas')
                         ],

                     ]
                 ],

             ]
         ];



 $menu['Pages'] = [

  'permission'=> [
  'system.pages.index',
  'system.pages.create',
  'system.pages.edit',
  ],
  'icon'=>'fa fa-map-marker',
  'text'=> __('Pages'),
  'sub'=>[
  [
  'permission'=> 'system.pages.index',
  'url'=> route('system.pages.index'),
  'text'=> __('View Pages')
  ],
  [
  'permission'=> 'system.pages.create',
  'url'=> route('system.pages.create'),
  'text'=> __('Create Page')
  ],

  [
  'aClass'=> 'color-red',
  'permission'=> 'system.pages.index',
  'url'=> route('system.pages.index',['withTrashed'=>1]),
  'text'=> __('Trashed Pages')
  ],
  ]
  ];


 $menu['Services'] = [

  'permission'=> [
  'system.services.index',
  'system.services.create',
  'system.services.edit',
  ],
  'icon'=>'fa fa-map-marker',
  'text'=> __('Services'),
  'sub'=>[
  [
  'permission'=> 'system.services.index',
  'url'=> route('system.services.index'),
  'text'=> __('View Services')
  ],
  [
  'permission'=> 'system.services.create',
  'url'=> route('system.services.create'),
  'text'=> __('Create Service')
  ],

  [
  'aClass'=> 'color-red',
  'permission'=> 'system.services.index',
  'url'=> route('system.services.index',['withTrashed'=>1]),
  'text'=> __('Trashed Services')
  ],
  ]
  ];

      $menu['System'] = [
             'permission'=> [
                  'system.setting.index',
                  'system.activity-log.index'
                  ],
             'class'=>'',
             'icon'=>'fa fa-cogs',
             'text'=>__('System'),
             'sub'=>[

                 'Setting'=> [
                     'permission'=> 'system.setting.index',
                     'icon'=> 'fa fa-cog',
                     'url'=> route('system.setting.index'),
                     'text'=> __('Setting'),
                 ],
                 'SendLog'=> [
                     'permission'=> 'system.sender.index',
                     'icon'=> 'fa fa-paper-plane',
                     'url'=> route('system.sender.index'),
                     'text'=> __('Send Log'),
                 ],


                 'ActivityLog'=> [
                     'permission'=> 'system.activity-log.index',
                     'icon'=> 'fa fa-binoculars',
                     'url'=> route('system.activity-log.index'),
                     'text'=> __('Activity Log'),
                 ],

             ]
         ];



@endphp

@foreach($menu as $onemenu)
    {!! generateMenu($onemenu) !!}
@endforeach