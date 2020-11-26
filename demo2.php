<?php


require './MsPDO.class.php';		//手动加载类文件
//测试
$param=array(
);
$mspdo= MsPDO::getInstance($param);
//echo $mypdo->exec('delete from news where id=6');
/*
if($mypdo->exec("insert into news values (null,'11','1111',unix_timestamp())"))
    echo '自动增长的编号是：'.$mypdo->lastInsertId ();
 */



$list=$mspdo->fetchAll('select * from test');
//$list=$mspdo->fetchRow('select * from test1');

echo '<pre>';
//var_dump($list);
echo '<table border="1" width="600" align="center">';
echo '<tr bgcolor="#dddddd">';
echo '<th>编号</th><th>书名</th><th>公司</th><th>地区</th><th>电话</th><th>EMALL</th>';
echo '</tr>';
foreach ($list as $key=>$value)
{
    echo '<tr>';
//foreach里面嵌套一个for循环也是可以的
    /*for($n=0;$n<count($value);$n++)
    {
        echo "<td>$value[$n]</td>";
    }*/
//foreach里面嵌套foreach

    foreach($value as $k=>$v)
    {
        echo "<td>{$k}-{$v}</td>";
    }
    echo '</tr>';
}
echo '</table>';

var_dump($list);

?>