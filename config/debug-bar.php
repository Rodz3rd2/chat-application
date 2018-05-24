<?php

return [
    'enabled' => filter_var(_env('DEBUG_BAR_ON', false), FILTER_VALIDATE_BOOLEAN),

    'tracy' => [
        'settings' => [
            'showPhpInfoPanel' => 1,
            'showSlimRouterPanel' => 1,
            'showSlimEnvironmentPanel' => 1,
            'showSlimRequestPanel' => 1,
            'showSlimResponsePanel' => 1,
            'showSlimContainer' => 1,
            'showEloquentORMPanel' => 1,
            'showTwigPanel' => 1,
            'showProfilerPanel' => 0,
            'showVendorVersionsPanel' => 0,
            'showIncludedFiles' => 0,
            'configs' => [
                // XDebugger IDE key
                'XDebugHelperIDEKey' => 'SUBLIME',
                // Disable login (don't ask for credentials, be careful) values ( 1 | 0 )
                'ConsoleNoLogin' => 0,
                // Multi-user credentials values( ['user1' => 'password1', 'user2' => 'password2'] )
                'ConsoleAccounts' => [
                    'dev' => '34c6fceca75e456f25e7e99531e2425c6c1de443'// = sha1('dev')
                ],
                // Password hash algorithm (password must be hashed) values('md5', 'sha256' ...)
                'ConsoleHashAlgorithm' => 'sha1',
                // Home directory (multi-user mode supported) values ( var || array )
                // '' || '/tmp' || ['user1' => '/home/user1', 'user2' => '/home/user2']
                'ConsoleHomeDirectory' => "",
                // terminal.js full URI
                'ConsoleTerminalJs' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.terminal/1.11.3/js/jquery.terminal.js',
                // terminal.css full URI
                'ConsoleTerminalCss' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.terminal/1.11.3/css/jquery.terminal.css',
                'ProfilerPanel' => [
                    // Memory usage 'primaryValue' set as Profiler::enable() or Profiler::enable(1)
                    // 'primaryValue' =>                   'effective',    // or 'absolute'
                    'show' => [
                        'memoryUsageChart' => 1, // or false
                        'shortProfiles' => false, // or false
                        'timeLines' => false // or false
                    ]
                ]
            ]
        ]
    ]
];