<?php
namespace App\Controllers;
class BoardRead extends BaseController
{  
   
    public function index()
    {
        $db = \Config\Database::connect("default");
        $result = $db->query('select id,author,content,updatetime,createtime from boardTable')->getResultArray();
        $result_json = json_encode($result);
        #var_dump($result_json);
        return $result_json;
    }
}


<?php
namespace App\Controllers;
class BoardWrite extends BaseController
{

    public function index()
    {
        parse_str(file_get_contents('php://input'),$post_vars);
        $db = \Config\Database::connect("default");
        foreach($post_vars as $key=>$value)
        {
            $obj = json_decode($key,true);
            $data=array_values($obj);
            print_r($data[1]);
            //return 0;
            $author=(string)$data[0];
            $content=(string)$data[1];
            $query = 'insert into boardTable(author, content) values("'.$author.'","'.$content.'");';
            $result=$db->query($query);
            return "success";
        }
    }
}

<?php
namespace App\Controllers;
class CalendarWrite extends BaseController
{

    public function index()
    {
        parse_str(file_get_contents('php://input'),$post_vars);
        $db = \Config\Database::connect("default");
        foreach($post_vars as $key=>$value)
        {
            $obj = json_decode($key,true);
            $data=array_values($obj);
            $content=(string)$data[0];
            $money=(int)$data[1];
            $year=$data[2];
            $month=$data[3];
            $day=$data[4];
            $query = 'insert into boardTable(content, money,year,month,day) values("'.$content.'",'.$money.','.$year.','.$month.','.$day.');';
            $result=$db->query($query);
            return "success";
        }
    }
}

<?php
namespace App\Controllers;
class CalendarRead extends BaseController
{  
    public function index()
    {
        $db = \Config\Database::connect("default");
        $result = $db->query('select content,money,year,month,day from calendar')->getResultArray();
        $result_json = json_encode($result);
        return $result_json;
    }
}