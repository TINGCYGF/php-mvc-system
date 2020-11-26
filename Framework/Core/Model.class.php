<?php
namespace Core;
//基础模型
class Model {
    protected $mspdo;
    private $table; //表名
    private $pk;    //主键
    public function __construct($table='') {
        $this->initMsPDO();
        $this->initTable($table);
        $this->getPrimaryKey();
    }
    //连接数据库
    private function initMsPDO() {
        $this->mspdo= MsPDO::getInstance($GLOBALS['config']['database']);
    }
    //获取表名
    private function initTable($table){
        if($table!='')		//直接给基础模型传递表名
            $this->table=$table;
        else {				//实例化子类模型
            $this->table=substr(basename(get_class($this)),0,-5);
        }
    }
    //获取主键
    private function getPrimaryKey() {
        $rs=$this->mspdo->fetchColumn("SELECT  COL_NAME(ic.OBJECT_ID,ic.column_id) AS ColumnName
FROM    sys.indexes AS i INNER JOIN 
        sys.index_columns AS ic ON  i.OBJECT_ID = ic.OBJECT_ID
                                AND i.index_id = ic.index_id
WHERE   i.is_primary_key = 1 and OBJECT_NAME(ic.OBJECT_ID) LIKE '%{$this->table}%'");
        $this->pk=$rs;
    }
    //万能的插入
    public function insert($data){
        $keys=array_keys($data);		//获取所有的字段名
        $keys=array_map(function($key){	//在所有的字段名上添加反引号
            return "{$key}";
        },$keys);
        $keys=implode(',',$keys);		//字段名用逗号连接起来
        $values=array_values($data);	//获取所有的值
        $values=array_map(function($value){	//所有的值上添加单引号
            return "'{$value}'";
        },$values);
        $values=implode(',',$values);	//值通过逗号连接起来
        $sql="insert into {$this->table} ($keys) values ($values)";
        return $this->mspdo->exec($sql);
    }
    //万能的更新
    public function update($data){
        $keys=array_keys($data);	//获取所有键
        $index=array_search($this->pk,$keys);	//返回主键在数组中的下标
        unset($keys[$index]);		//删除主键
        $keys=array_map(function($key) use ($data){
            return "{$key}='{$data[$key]}'";
        },$keys);
        $keys=implode(',',$keys);
        $sql="update {$this->table} set $keys where $this->pk={$data[$this->pk]}";
        return $this->mspdo->exec($sql);
    }
    //删除
    public function delete($id){
        $sql="delete from {$this->table} where {$this->pk}=$id";
        return $this->mspdo->exec($sql);
    }
    //查询,返回二维数组
    public function select($cond=array()){
        $sql="select * from {$this->table} ";
        if(!empty($cond)){
            foreach($cond as $k=>$v){
                if(is_array($v)){	//条件的值是数组类型
                    switch($v[0]){	//$v[0]保存的是符号，$v[1]是值
                        case 'eq':		//等于  equal
                            $op='=';
                            break;
                        case 'gt':		//大于  greater than
                            $op='>';
                            break;
                        case 'lt':
                            $op='<';
                            break;
                        case 'gte':
                        case 'egt':
                            $op='>=';
                            break;
                        case 'lte':
                        case 'elt':
                            $op='<=';
                            break;
                        case 'neq':
                            $op='<>';
                            break;
                    }
                    $sql.=" and `$k` $op '$v[1]'";
                }else{
                    $sql.=" and `$k`='$v'";
                }
            }
        }
        return $this->mspdo->fetchAll($sql);
    }
    //查询，返回一维数组
    public function find($id){
        $sql="select * from {$this->table} where {$this->pk}='$id'";
        return $this->mspdo->fetchRow($sql);
    }
}