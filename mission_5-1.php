<html>
    <head>
    <title>mission_5-1</title>
    <meta charset = "utf-8">
    </head>
    <body>

<?php
    
    //接続
    $dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS info"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password TEXT"
    .");";
    //sqlの実行
    $stmt = $pdo->query($sql);
    
    //全体
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
        if(empty($_POST["editNo"])){
            
            //通常insert
            $sql = $pdo -> prepare("INSERT INTO info (name, comment, password) VALUES (:name, :comment, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $password = $_POST["password"];
            $sql -> execute();
        }else{
            //編集
            //bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
            $id = $_POST["editNo"]; //変更する投稿番号
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $password = $_POST["password"];
            $sql = 'update info set name=:name,comment=:comment,password=:password where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

        }
    }
    //削除
    if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
        $delete = $_POST["delete"];
        $delpass = $_POST["delpass"];
        //selectで取り出す
        $sql = 'SELECT * FROM info';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['password'] == $delpass && $row['id'] == $delete){
                //削除
                $id = $delete;
                $sql = 'delete from info where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            
        }
    }
    
    //番号を入力した時にhiddenに数字を入れる
    if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $edit = $_POST["edit"];
        $editpass = $_POST["editpass"];
        //selectで取り出す
        $sql = 'SELECT * FROM info';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        //フォームに名前とコメントを代入
        foreach ($results as $row){
            if($row['id'] == $edit && $row['password'] == $editpass){
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $edpass = $row['password'];
               
            }
        }
    }

        
        
    
    ?>
<form method = "post" action = "mission_5-1.php" >
<input name = "name" type = "text" placeholder = "名前" value="<?php if(isset($editname)) {echo $editname;} ?>"> <br/>
<input name = "comment" type = "text" placeholder = "コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"> <br/>
<input name = "password" type = "text" placeholder = "パスワード"value="<?php if(isset($edpass)) {echo $edpass;} ?>">
<input name = "btn" type = "submit" >
<input name = "editNo" type = "hidden"  value="<?php if(isset($edit)) {echo $edit;} ?>">
</form>
<form method = "post" action = "mission_5-1.php" >
<input name = "delete" type = "text" placeholder = "削除番号"><br/>
<input name = "delpass" type = "text" placeholder = "パスワード">
<input name = "deletebtn" type = "submit" value = "削除">
</form>
<form method = "post" action = "mission_5-1.php" >
<input name = "edit" type = "text" placeholder = "編集対象番号"> <br/>
<input name = "editpass" type = "text" placeholder = "パスワード">
<input name = "editbtn" type = "submit" value = "編集">
</form>
<?php
    //select
    //$rowの添字（[ ]内）は4-2でどんな名前のカラムを設定したかで変える必要がある
    $sql = 'SELECT * FROM info';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].'<br>';
        echo "<hr>";
    }
?>
</body>
</html>


