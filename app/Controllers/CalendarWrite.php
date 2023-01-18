<?php
namespace App\Controllers;
class CalendarWrite extends BaseController
{

    public function index()
    {   
        date_default_timezone_set('Asia/Seoul');
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
            $category=$data[5];
            $query = 'insert into calendar(content, money,year,month,day,category) values("'.$content.'",'.$money.','.$year.','.$month.','.$day.',"'.$category.'");';
            $result=$db->query($query);
            return "success";
        }
    }
}