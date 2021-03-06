<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/<?php 
                        if(Yii::$app->user->identity->administrador) echo "administrador"; 
                        else if(Yii::$app->user->identity->coordenador) echo "coordenador"; 
                        else if(Yii::$app->user->identity->professor) echo "professor"; 
                        else if(Yii::$app->user->identity->secretaria) echo "secretaria"; 
                        else echo "aluno"; 
                    ?>.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->nome ?></p>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'items' => [
                ['label' => 'Início','icon' => 'fa fa-home', 'url' => ['site/index'],],
                ['label' => 'Administração', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('administrador')],
                [
                    'label' => 'Usuários',
                    'icon' => 'fa fa-users',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria')),
                    'items' => [
                        ['label' => 'Adicionar Usuário', 'icon' => 'fa fa-user-plus', 'url' => ['site/signup'],],
                        ['label' => 'Listar Usuários', 'icon' => 'fa fa-list', 'url' => ['user/index'],],
                    ],
                ],
                ['label' => 'Coordenação PPGI', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('coordenador')],
                [
                    'label' => 'Reserva de Sala',
                    'icon' => 'fa fa-building-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenador'),
                    'items' => [
                        ['label' => 'Gerenciar Salas', 'icon' => 'fa fa-wrench', 'url' => ['sala/index'],],
                        ['label' => 'Reservar Sala', 'icon' => 'fa fa-list', 'url' => ['reserva-sala/index'],],
                    ],
                ],
                [
                    'label' => 'Seleções PPGI',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenador') || Yii::$app->user->identity->checarAcesso('secretaria'),
                    'items' => [
                        ['label' => 'Criar Edital de Seleção', 'icon' => 'fa fa-file-code-o', 'url' => ['edital/create'],],
                        ['label' => 'Listar Editais de Seleção', 'icon' => 'fa fa-list', 'url' => ['edital/index'],],
                    ],
                ],
                [
                    'label' => 'Gerenciar Defesas',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenador'),
                    'items' => [
                        ['label' => 'Defesas a serer avaliadas', 'icon' => 'fa fa-list', 'url' => ['banca-controle-defesas/index'],],
                        ['label' => 'Listar todas as defesas', 'icon' => 'fa fa-list', 'url' => ['defesa/index'],],
                    ],
                ],
                [
                    'label' => 'Linhas de Pesquisa',
                    'icon' => 'fa fa-search',
                    'url' => '#',
                    'visible' => (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('coordenador')),
                    'items' => [
                        ['label' => 'Adicionar Linha de Pesquisa', 'icon' => 'fa fa-search-plus', 'url' => ['linha-pesquisa/create'],],
                        ['label' => 'Listar Linhas de Pesquisa', 'icon' => 'fa fa-list', 'url' => ['linha-pesquisa/index'],],
                    ],
                ],
                ['label' => 'Professor', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('professor')],
                [
                    'label' => 'Afastamento Temporário',
                    'icon' => 'fa fa-plane',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('professor'),
                    'items' => [
                        ['label' => 'Solicitar Afastamento', 'icon' => 'fa fa-calendar-plus-o', 'url' => ['afastamentos/create'],],
                        ['label' => 'Meus Afastamentos', 'icon' => 'fa fa-list', 'url' => ['afastamentos/index'],],
                    ],
                ],
                ['label' => 'Minhas Férias', 'icon' => 'fa fa-sun-o', 'url' => ['ferias/listar', "ano" => date("Y") ], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],
                ['label' => 'Acompanhar Orientandos', 'icon' => 'fa fa-eye', 'url' => ['aluno/orientandos'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],
                ['label' => 'Upload Lattes', 'icon' => 'fa fa-upload', 'url' => ['user/lattes'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],

                ['label' => 'Secretaria', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('secretaria')],
                ['label' => 'Alunos', 'icon' => 'fa fa-file-code-o', 'url' => ['aluno/index'], 'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),],
                [
                    'label' => 'Gerenciar Férias',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),
                    'items' => [
                        ['label' => 'Minhas Férias', 'icon' => 'fa fa-list', 'url' => ['ferias/listar', "ano" => date("Y") ],],
                        ['label' => 'Controlar Férias', 'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),  'icon' => 'fa fa-list', 'url' => ['ferias/listartodos', "ano" => date("Y") ],],
                    ],
                ],
                [
                    'label' => 'Reserva de Sala',
                    'icon' => 'fa fa-building-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),
                    'items' => [
                        ['label' => 'Gerenciar Salas', 'icon' => 'fa fa-wrench', 'url' => ['sala/index'],],
                        ['label' => 'Reservar Sala', 'icon' => 'fa fa-list', 'url' => ['reserva-sala/index'],],
                    ],
                ],
                ['label' => 'Gerenciar Defesas', 'icon' => 'fa fa-file-code-o', 'url' => ['defesa/index'], 'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),],
                [
                    'label' => 'Afastamento Temporário',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),
                    'items' => [
                        ['label' => 'Solicitar Afastamento', 'icon' => 'fa fa-file-code-o', 'url' => ['afastamentos/create'],],
                        ['label' => 'Listar Afastamentos', 'icon' => 'fa fa-list', 'url' => ['afastamentos/index'],],
                    ],
                ],
                ['label' => 'Adicionar Membros', 'icon' => 'fa fa-file-code-o', 'url' => ['membros-banca/create'], 'visible' => Yii::$app->user->identity->checarAcesso('secretaria'),],
               // ['label' => 'Aluno', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('aluno')],
               // ['label' => 'Aluno Opção', 'icon' => 'fa fa-file-code-o', 'url' => ['site/index'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],
            ],
        ]) ?>
    </section>

</aside>
