<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Convert
{
    private $rules;

    public function __construct()
    {
        $this->rules = [
            '0'=>['table'=>"user.name", 'default'=>"-----"],
            '1'=>['table'=>"user.sex", 'default'=>"-----"],
            '2'=>['table'=>"designer.name", 'default'=>"-----"],
            '3'=>['table'=>"user.birth", 'default'=>"-----"],

            '6'=>['table'=>"user.phone", 'default'=>"-----"],
        ];
    }

    private function preparatory($rows)
    {
        // 컬럼필트 매칭
        $sql = [];
        foreach($rows as $i => $row) {
            foreach($row as $key => $value) {
                // migration rule이 설정된 경우
                if(isset($this->rules[$key])) {
                    $temp = explode('.',$this->rules[$key]['table']);
                    $tablename = $temp[0];
                    $colname = $temp[1];

                    if($value) {
                        $sql[$tablename][$i][$colname] = $value;
                    } else {
                        $sql[$tablename][$i][$colname] = $this->rules[$key]['default'];
                    }

                 }
            }
        }

        return $sql;
    }

    public function build($rows)
    {
        $sql = $this->preparatory($rows);

        // dd($sql);
        $query=[];
        foreach($sql as $table => $_rows) {

            foreach($_rows as $_row) {
                $qqq = "INSERT INTO ".$table." SET ";
                foreach($_row as $col => $value) {
                    $qqq .= $col."='".$value."',";
                }
                $qqq = rtrim($qqq,',').";";
                $query []= $qqq;
            }

        }

        return $query;
    }



}
