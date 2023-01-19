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
            $q=str_replace('_',' ',$query);
            $result=$db->query($q);
            return $result;
        }
    }
}