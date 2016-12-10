<?php
    error_reporting(E_ALL);

    require_once "TasksDB.class.php";

    // массив ошибок:
    // ключ - тип ошибки (success, info, warning, danger)
    // значение - текст ошибки
    $errMsgs = array();

    $userName = '';
    $tasks = '';
    $user = '';

    if(!empty($_GET['user'])){
        $user = $_GET['user'];
        $tasksObj = new TasksDB($user);
    }else{
        $errMsgs[] = array("warning" => "Не выбранна база данных сотрудника");
    }

    if(!empty($_GET['save_task']))
    {
        $save_task = $_GET['save_task'];

        $post_data = array(
            'title'         => $_POST['title'],
            'link'          => $_POST['link'],
            'site'          => $_POST['site'],
            'deadline'      => $_POST['deadline'],
            'description'   => $_POST['description'],
            'small_note'    => $_POST['small_note'],
            'full_note'     => $_POST['full_note'],
            'status'        => $_POST['status']
        );

        if($save_task == 'new'){
            $tasks = $tasksObj->saveTask($post_data); // сохраняем задание
        }else{
            $tasks = $tasksObj->saveTask($post_data, $save_task); // обновляем задание
        }

    }

    if(!empty($_GET['edit_id'])){
        $tasksObj->getTask($_GET['edit_id'], true);
    }

    if(!empty($_GET['remove_id'])){
        $tasksObj->deleteTask($_GET['remove_id']);
    }

    if(!empty($_GET['reorder']) AND !empty($_GET['tasks_table'])){
        if(!$tasksObj->reorderTasks($_GET['tasks_table'])){
            $errMsgs[] = array("danger" => "Ошибка при изменении порядка заданий");
        }
    }

    if(!empty($_GET['changeUserName']))
    {
        $userName = $_GET['changeUserName'];
        if($tasksObj->changeUserName($userName)){
            $errMsgs[] = array("danger" => "Ошибка при записи имени сотрудника");
        }
    }

    if(!empty($_GET['db_view'])){
        $db_view = $tasksObj->db_view();
    }

    $users = TasksDB::getUsers();
    if(!$users){
        $errMsgs[] = array("warning" => "Не найдены базы данных сотрудников");
    }

    if(isset($tasksObj))
    {
        $tasks = $tasksObj->getTasks();

        if($tasks === false){
            $errMsgs[] = array("danger" => "Ошибка при получении данных заданий");
        }elseif(!$tasks){
            $errMsgs[] = array("warning" => "Задания не найдены");
        }

        $userName = $tasksObj->getUserName();
        if(!$userName){
            $userName = 'Новый сотрудник';
        }
    }

include "template.php";


