<?php

namespace App\Controllers;
class BoardRead extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect("default");
	    $result = $db->query('select id,author,content,updatetime,createtime from boardTable order by createtime desc;')->getResultArray();
	    $result_json = json_encode($result);
        return $result_json;
    }
}