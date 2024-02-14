<?php

return [

    'table_names' =>  [
        'user_table'                    =>  'users', // user table name on main LMS app.
        'resource_table'                =>  'resource', // resource table on LMS app.
        'scorm_table'                   =>  'scorm',
        'scorm_sco_table'               =>  'scorm_sco',
        'scorm_sco_tracking_table'      =>  'scorm_sco_tracking',
    ],


    'disk'       => env('FILESYSTEM_SCORM_DISK', 'scorm-local'),
    'archive'       => env('FILESYSTEM_SCORM_DISK', 'scorm-local'),
];