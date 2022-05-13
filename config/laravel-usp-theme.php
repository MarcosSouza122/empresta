<?php

$submenu1 = [
    [
        'type' => 'header',
        'text' => '<i class="far fa-bookmark"></i> Categoria',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Categoria',
        'url' => 'categorias/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Categorias',
        'url' => 'categorias',
        'can' => 'admin'
    ],
    [
        'type' => 'divider',
        'can' => 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-boxes"></i> Material',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Material',
        'url' => 'materials/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Material',
        'url' => 'materials',
        'can' => 'admin'
    ],
    [
        'type' => 'divider',
        'can' => 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-eye"></i> Visitantes',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Visitante',
        'url' => 'visitantes/create',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Visitantes',
        'url' => 'visitantes',
        'can' => 'balcao'
    ],
    [
        'type' => 'divider',
        'can' => 'admin'
    ],
    [
        'type' => 'header',
        'text' => '<i class="fas fa-users"></i> Usuários',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-plus-circle"></i> Cadastrar Usuário',
        'url' => 'users/create',
        'can' => 'admin'
    ],
    [
        'text' => '<i class="fas fa-list-ul"></i> Listar Usuário',
        'url' => 'users',
        'can' => 'admin'
    ],
];

$submenu2 = [
    [
        'text' => '<i class="fas fa-undo"></i> Devolução',
        'url' => 'emprestimos/devolucao',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-handshake"></i> Empréstimo USP',
        'url' => 'emprestimos/usp',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="far fa-handshake"></i> Empréstimo Visitante',
        'url' => 'emprestimos/visitante',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-stream"></i> Itens Emprestados',
        'url' => 'emprestimos',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-chart-bar"></i> Relatório de Empréstimos',
        'url' => 'emprestimos/relatorio',
        'can' => 'balcao'
    ],
    [
        'text' => '<i class="fas fa-barcode"></i> Código de Barras',
        'url' => 'categorias/barcode',
        'can' => 'admin'
    ],

];
$menu = [
    [
        'text' => '<i class="fas fa-user-cog"></i> Administração',
        'submenu' => $submenu1,
        'can' => 'balcao',
    ],
    [
        'text' => '<i class="fas fa-retweet"></i> Empréstimos/Devolução',
        'submenu' => $submenu2,
        'can' => 'balcao',
    ],
];

$right_menu = [
    [
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'target' => '_blank',
        'url' => 'item1',
        'align' => 'right',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-hard-hat"></i>',
        'title' => 'Logs',
        'target' => '_blank',
        'url' => 'logs',
        'align' => 'right',
        'can' => 'admin',
    ],
];

# dashboard_url renomeado para app_url
# USPTHEME_SKIN deve ser colocado no .env da aplicação 

return [
    'title' => config('app.name'),
    'skin' => env('USP_THEME_SKIN', 'uspdev'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login_intern',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
