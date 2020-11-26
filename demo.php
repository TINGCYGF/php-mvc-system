<?php
   $dbName = "sqlsrv:Server=127.0.0.1,1433;Database=TING2;";   //这里是服务器IP地址和数据库名，端口不是默认的话记得改一下
   $dbUser = "TING";    //用户名
   $dbPassword = "1127163161";    //登陆密码

   $pdo = new PDO($dbName, $dbUser, $dbPassword);   
    
   if ($pdo)   
  {       
     echo "连接成功<br />";   
   }
//新增表内容语句
if($pdo->exec("INSERT INTO test1 VALUES('026','三国志','HMJD',36,60);"))
   echo $pdo->lastInsertId(),'<br>';
?>