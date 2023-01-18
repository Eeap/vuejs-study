<?php
namespace App\Controllers;
class CalendarEdit extends BaseController
{
    public function index()
    {
	    date_default_timezone_set('Asia/Seoul');
        parse_str(file_get_contents('php://input'),$post_vars);
	    $db = \Config\Database::connect("default");
	    $id=$_GET['id'];
        foreach($post_vars as $key=>$value)
        {
            $obj = json_decode($key,true);
            $data=array_values($obj);
            $money=$data[1];
            $content=$data[2];
            $year=$data[3];
            $month=$data[4];
            $day=$data[5];
            $category=$data[6];
            $query = 'update calendar set money='.$money.', content="'.$content.'",year='.$year.', month='.$month.', day='.$day.', time="'.date("Y-m-d H:i:s").'", category="'.$category.'" where id='.$id.';';
            $result=$db->query($query);
            return "success";
        }
    }
}