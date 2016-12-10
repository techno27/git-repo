<!DOCTYPE HTML>

<html>

<head>
    <title>Менеджер заданий</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery-ui.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="js/jquery.tablednd.js"></script>-->
    <script src="js/jquery.tablednd-ext.js"></script>
    <script src="js/unserialize.jquery.latest.js"></script>
    <script src="js/script.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="main">


                <!-- Выбор сотрудника -->
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        Cотрудник <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <? if($users): ?>
                            <? foreach($users as $keyUser => $nameUser): ?>
                                <li><a href="<?= $keyUser; ?>"><?= $nameUser; ?></a></li>
                            <? endforeach; ?>
                            <li role="separator" class="divider"></li>
                        <? endif; ?>
                        <li><a href="" id="addUser" onclick="return false;">Добавить сотрудника</a></li>
                    </ul>
                </div>
                <!-- /Выбор сотрудника -->


                <h4 class="sub-header">Текущие задания</h4>


                <h5 class="sub-header" <? if(!$userName) echo 'style="display: none;"'?> id="userInfo">
                    <span class="glyphicon glyphicon-user"></span>
                    Сотрудник:
                    <span id="userData">
                        <strong id="userNameValue" class=""><?= $userName; ?></strong>
                        <button type="button" class="btn btn-default btn-xxs" id="changeUserName" title="Изменить имя">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                    </span>
                    <div id="userChangeForm" style="display: none;">
                        <form action="" class="form-inline">
                            <input type="text" size="40" name="changeUserName" placeholder="Фамилия Имя" class="form-control input-sm"/>
                            <input type="text" size="20" name="user" placeholder="Имя БД (латиница)" class="form-control input-sm" style="display: none;" value="<?= $user; ?>"/>
                            <input type="submit" name="send" value="Записать" class="form-control input-sm"/>
                            <input type="button" name="cancel" value="Отменить" class="form-control input-sm"/>
                        </form>
                    </div>
                </h5>

                <div class="table-responsive" id="ajaxOverwrite">

                    <? if($errMsgs): ?>
                        <? foreach($errMsgs as $errMsg): ?>
                            <div class="alert alert-<?= key($errMsg); ?>">
                              <span class="glyphicon glyphicon-exclamation-sign"></span>
                              <?= current($errMsg); ?>
                            </div>
                        <? endforeach; ?>
                    <? endif; ?>

                    <? if($tasks): ?>
                        <table class="table table-striped table-bordered table-hover table-condensed" id="tasks_table">
                            <thead>
                                <tr class="info nodrop nodrag">
                                    <th class="text-center" id="col-1">#</th>
                                    <th id="col-2">Заголовок</th>
                                    <th id="col-3">Сайт</th>
                                    <th class="text-center" id="col-4">Deadline</th>
                                    <th id="col-5">Комментарий</th>
                                    <th class="text-center" id="col-6">Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $counter = 0;
                                foreach($tasks as $task):
                                    $counter++;
                                    ?>
                                    <tr class="<?php echo $task['status_class']?>" id="table5-row-<?php echo $task['id']; ?>">
                                        <td class="text-center"><button class="btn btn-sm btn-default edit_btn" value="<?php echo $task['id']; ?>"><?php echo $counter; ?></button></td>
                                        <td class="title"><?php echo $task['title']; ?></td>
                                        <td class="site"><?php echo $task['site']; ?></td>
                                        <td class="text-center"><?php echo $task['deadline']; ?></td>
                                        <td><?php echo $task['full_note']; ?></td>
                                        <td class="text-center col-status"><?php echo $task['status']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            ?>
                            </tbody>
                        </table>
                    <? endif; ?>

                </div>

                <? if($userName): ?>
                <button class="btn btn-primary btn-default pull-right" data-toggle="modal" data-target="#myModal"  id="new_task">
                    Добавить задание
                </button>
                <? endif; ?>

            </div>
        </div>

    </div>

    <!-- Modal -->
    <?php include('modal.php'); ?>
    <!-- /Modal -->

    <? if(!empty($db_view)) echo $db_view; ?>

</body>

</html>