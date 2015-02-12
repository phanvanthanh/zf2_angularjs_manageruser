<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'ManagerUser\Controller\ManagerUser' => 'ManagerUser\Controller\ManagerUserController',

         ),
     ),

     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
            'manager-user' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/manager-user[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ManagerUser\Controller\ManagerUser',
                    ),
                ),
            ),
         ),
     ),

     'view_manager' => array(
         'template_path_stack' => array(
             'manager_user' => __DIR__ . '/../view',
         ),
    
 )
     );
?>