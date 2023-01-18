<?php
namespace App\Controllers;
class CalendarRead extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect("default");
        $result = $db->query('select id,content,money,year,month,day,time,category from calendar')->getResultArray();
	    $result_json = json_encode($result);
        #var_dump($result_json);
        return $result_json;
    }
}