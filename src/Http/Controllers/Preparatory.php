<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Preparatory extends Controller
{
    private $folder = "import";

    public function index(Request $request)
    {
        $inputFileName = storage_path('app/'.$this->folder).DIRECTORY_SEPARATOR.$request->uuid;
        $inputFileName .= DIRECTORY_SEPARATOR."customer.xlsx";
        //dd($inputFileName);
        if(file_exists($inputFileName)) {
            // 엑셀파일 읽기
            $Excel = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel();
            $datas = $Excel->load($inputFileName);

            $title = $datas[1];
            $rows = [];
            for($i=2; $i<count($datas); $i++) {
                $rows []= $datas[$i];
            }
            unset($datas);

            // 1. 엑셀 디자이너 목록 추출
            $designer = $this->excelDesigner($rows);

            // 2. 샵소속 디자이너 목록
            $deginerList = $this->shopDesigners($request->uuid);
            foreach($deginerList as $item) {
                $key = $item->display_name;
                if(isset($designer[$key])) {
                    $designer[$key] = false;
                }
            }

            // a. 사용자등록>디자이너등록
            $users = $this->insertUser($designer);
            $this->insertDesginer($users, $request->uuid);


            return view("excel::convert",['designer'=>$designer]);
        }

        return "customer.xlsx 파일이 없습니다.";
    }

    private function getKeyGen()
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $var_size = strlen($chars);

        $gen = "";

        for( $x = 0; $x < 8; $x++ ) {
            $gen .= $chars[ rand( 0, $var_size - 1 ) ];
        }
        $gen .= "-";

        for($j=0;$j<3;$j++){
            for( $x = 0; $x < 4; $x++ ) {
                $gen .= $chars[ rand( 0, $var_size - 1 ) ];
            }
            $gen .= "-";
        }

        for( $x = 0; $x < 12; $x++ ) {
            $gen .= $chars[ rand( 0, $var_size - 1 ) ];
        }

        return $gen;
    }

    private function insertDesginer($users, $shop_id)
    {
        $shop = DB::table('xe_quantum_shop')->where('shop_id', $shop_id)->first();

        $designer = [];
        foreach($users as $user){
            $designer []= [
                'user_id'=>$user['id'],
                'smart_mall_seller_id' => $shop->smart_mall_seller_id,
                'display_name'=>$user['display_name'],
                'designer_type'  => 1,
                'belongs_to_shop' => 1,
                'status' => 1,
                'rating'=>0,
                'created_at'=> date("Y-m-d H:i:s"),
                'updated_at'=> date("Y-m-d H:i:s")
            ];
        }

        //dd($designer);
        DB::table('xe_quantum_designer')->insert($designer);

        $designerShop = [];
        foreach($users as $user){
            $designerShop []= [
                'shop_id' => $shop_id,
                'designer_id' => $user['id'],
                'created_at'=> date("Y-m-d H:i:s"),
                'updated_at'=> date("Y-m-d H:i:s")



            ];
        }
        DB::table('xe_quantum_designer_in_shop')->insert($designerShop);

        return $designer;
    }

    private function insertUser($designer)
    {
        $max = DB::table('xe_user')->count();
        $users = [];
        foreach($designer as $name => $status) {
            // 신규 삽입, false = 중복유저
            if($status == true) {
                $users []= [
                    'id' => $this->getKeyGen(),
                    'display_name' => $name,
                    'email' => "a",
                    'login_id'=> $max++,
                    'password'=> "a",
                    'rating'=> "a",
                    'status'=> "a",
                    'introduction'=> "a",
                    'created_at'=> date("Y-m-d H:i:s"),
                    'updated_at'=> date("Y-m-d H:i:s")
                ];
            }
        }

        DB::table('xe_user')->insert($users);
        return $users;
    }

    private function shopDesigners($shop_id)
    {
        // 샵소속 디자이너 목록
        $deginers = DB::table('xe_quantum_designer_in_shop')->where('shop_id', $shop_id)->get();
        $deginerIds = [];
        foreach($deginers as $item) {
            $deginerIds []= $item->designer_id;
        }
        //dd($deginerIds);
        $deginerList = DB::table('xe_quantum_designer')->whereIn('user_id', $deginerIds)->get();
        // dd($deginerList);
        return $deginerList;
    }

    private function excelDesigner($rows)
    {
        $desiner = [];
        foreach($rows as $row) {
            // xe_quantum_designer
            //$desiner []= ['display_name'=>$row[2] ];
            $row[2]?$name = $row[2]:$name="이름없음";
            $desiner[$name] = true;
        }
        return $desiner;
    }




}
