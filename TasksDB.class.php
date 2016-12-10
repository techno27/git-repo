<?php
error_reporting(E_ALL);

class TasksDB
{
    private $_db = null;

    function __get($name){
        if($name == "db"){
            return $this->_db;
        }
        throw new Exception("Unknown property");
    }

    function __construct($user){
        $user = 'DBs/'.$user.'.db';
        $this->_db = new SQLite3($user);
        if(filesize($user) == 0){
            try{
                $sql_1 = "
                    CREATE TABLE tasks_data(
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        title TEXT,
                        link TEXT,
                        site TEXT,
                        deadline TEXT,
                        description TEXT,
                        small_note TEXT,
                        full_note TEXT,
                        status TEXT,
                        orderNumber INTEGER
                    )
                ";

                if(!$this->_db->exec($sql_1))
                    throw new Exception($this->_db->lastErrorMsg());
            }catch (Exception $e){
                $e->getMessage();
                echo "Ошибка!";
            }

            try{
                $sql_2 = "
                    CREATE TABLE user_data(
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        fio TEXT
                    )
                ";
                if(!$this->_db->exec($sql_2))
                    throw new Exception($this->_db->lastErrorMsg());
            }catch (Exception $e){
                    $e->getMessage();
                    echo "Ошибка!";
           }

        } // end if filesize
    }

    function __destruct(){
        unset($this->_db);
    }

    function saveTask($post_data, $id = null)
    {
        $title          = $this->clearStr($post_data['title']);
        $link           = $this->clearStr($post_data['link']);
        $site           = $this->clearStr($post_data['site']);
        $deadline       = $this->clearStr($post_data['deadline']);
        $description    = $this->clearStr($post_data['description']);
        $small_note     = $this->clearStr($post_data['small_note']);
        $full_note      = $this->clearStr($post_data['full_note']);
        $status         = $this->clearStr($post_data['status']);

        if($id){
            $id = $this->clearInt($id);
            $sql = "
                UPDATE
                    tasks_data
                SET
                    title = '$title',
                    link = '$link',
                    site = '$site',
                    deadline = '$deadline',
                    description = '$description',
                    small_note = '$small_note',
                    full_note = '$full_note',
                    status = '$status'
                WHERE
                    id = $id
            ";
        }else{
            $sql = "
                INSERT INTO tasks_data(
                    title,
                    link,
                    site,
                    deadline,
                    description,
                    small_note,
                    full_note,
                    status,
                    orderNumber
                )
                VALUES(
                    '$title',
                    '$link',
                    '$site',
                    '$deadline',
                    '$description',
                    '$small_note',
                    '$full_note',
                    '$status',
                    9999
            )";
        }

        return $this->_db->exec($sql);
    }

    private function db2Arr($data){
        $arr = array();
        while($row = $data->fetchArray(SQLITE3_ASSOC)){
            $arr[] = $row;
        }
        return $arr;
    }

    // Возвращает задание по ID,
    // без ID - вернет все задания
    function getTask($id = null, $json_encode = false, $debug = false)
    {
        $where = '';
        if($id) $where = ' WHERE id = '.$id;

        $orderBy = 'ORDER BY ';
        if($debug){
            $orderBy .= 'id ASC';
        }else{
            $orderBy .= 'orderNumber ASC, id ASC';
        }

        $sql = "
            SELECT
                id,
                title,
                link,
                site,
                deadline,
                description,
                small_note,
                full_note,
                status,
                orderNumber
            FROM
                tasks_data
            $where
            $orderBy
        ";

        $tasksRaw = $this->_db->query($sql);

        if(!$tasksRaw){
            return false;
        }else{
            $tasksRaw = $this->db2Arr($tasksRaw);
            if($tasksRaw === false){
                return false;
            }
        }

        if($json_encode){
            echo json_encode($tasksRaw[0]);
            exit;
        }

        return $tasksRaw;
    }

    // Возвращает все задания, + делает форматирование для списка
    function getTasks()
    {
        $tasksRaw = $this->getTask();

        if(!$tasksRaw) return $tasksRaw;

        // Подготовка данных для вывода в шаблоне
        $tasks = array();
        foreach($tasksRaw as $task)
        {
            if($task['link']){
                $task['title'] = '<a href="'.$task['link'].'" target="_blank">'.$task['title'].'</a>';
            }

            if($task['small_note']){
                $task['title'] = '<span class="label label-info">'.$task['small_note'].'</span> '.$task['title'];
            }

            if($task['description']){
                $task['title'] = $task['title'].' <a id="toggle_description" href="#">&raquo;</a><div class="description">'.$task['description'].'</div>';
            }

            switch ($task['status']){
                case "В работе":    $task['status_class'] = "in_work"; break;
                case "Жду ответа":  $task['status_class'] = "waiting"; break;
                case "Выполнил":    $task['status_class'] = "done"; break;
                case "Отменено":    $task['status_class'] = "canceled"; break;
                default:            $task['status_class'] = "default";
            }

            $tasks[] = $task;
        }

        return $tasks;

    }

    function deleteTask($id){
        $id = $this->clearInt($id);
        $sql = "DELETE FROM tasks_data WHERE id = ".$id;
        return $this->_db->exec($sql);
    }

    function reorderTasks($newOrderIds){
        foreach($newOrderIds as $newOrderNumber => $id){
            $newOrderNumber++;
            $sql = "UPDATE tasks_data SET orderNumber = $newOrderNumber WHERE id = $id";
            $result = $this->_db->exec($sql);
        }

        return $result;
    }

    function getUserName(){
        $sql = "SELECT fio FROM user_data";
        $result = $this->_db->querySingle($sql);
        if(!$result){
            return false;
        }else{
            return $result;
        }
    }

    function changeUserName($userName){
        $userName = $this->clearStr($userName);
        if($this->getUserName()){   //если имя заданно - изменить его
            $sql = "UPDATE user_data SET fio = '$userName' WHERE id = 1";
        }else{  // иначе - вставить имя
            $sql = "INSERT INTO user_data (fio) VALUES ('$userName')";
        }
        $result = $this->_db->exec($sql);
        return $result;
    }

    function clearStr($data){
        $data = strip_tags($data);
        return $this->_db->escapeString($data);
    }

    function clearInt($data){
        return abs((int)$data);
    }

    function db_view(){
        $tasks = $this->getTask(false, false, true);

        $tasksStr = '';
        $tasksColumnHeader = '';
        $tasksRows = '';

        foreach($tasks[0] as $key => $task){
            $tasksColumnHeader .= "<th>$key</th>";
        }
        $tasksColumnHeader = "<tr class='info'>$tasksColumnHeader</tr>";
        $tasksColumnHeader = "<thead>$tasksColumnHeader</thead>";

        foreach($tasks as $task){
            foreach($task as $taskFieldValue){
                $tasksRows .= "<td>$taskFieldValue</td>";
            }
            $tasksRows = "<tr>$tasksRows</tr>";
        }
        $tasksRows = "<tbody>$tasksRows</tbody>";

        $tableClasses = 'class="table table-striped table-bordered table-hover table-condensed"';
        $tasksStr = $tasksColumnHeader.$tasksRows;
        $tasksStr = "<table $tableClasses>$tasksStr</table>";
        $tasksStr = "<div class='container'>$tasksStr</div>";


        return $tasksStr;
    }

    static function getDBs()
    {
        $usersDB = array();
        if ($handle = opendir('DBs')) {
            while (false !== ($entry = readdir($handle))) {
                if(strpos($entry, 'db')){
                    $usersDB[] = substr($entry, 0, -3);
                }
            }
            closedir($handle);
        }

        if(!$usersDB) return false;

        return $usersDB;
    }

    static function getUsers()
    {
        $users = array();
        $usersDBName = self::getDBs();

        if(!$usersDBName) return false;

        foreach($usersDBName as $userDBName){
            $taskObj = new TasksDB($userDBName);
            $userName = $taskObj->getUserName();
            if(!$userName) $userName = 'Новый сотрудник';
            $users['?user='.$userDBName] = $userName;
            unset($taskObj);
        }
        return $users;
    }

}
