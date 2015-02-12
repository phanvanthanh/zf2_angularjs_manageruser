<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'Login\Controller\login' => 'Login\Controller\LoginController',

         ),
     ),

     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
            'login' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/login[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Login\Controller\Login',
                    ),
                ),
            ),
         ),
     ),

     'view_manager' => array(
         'template_path_stack' => array(
             'login' => __DIR__ . '/../view',
         ),
    
 )
     );
?>