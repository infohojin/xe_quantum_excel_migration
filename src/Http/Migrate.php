<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Migrate
{
    public function checkDeginers($rows, $shop_id)
    {
        // 1. 엑셀 디자이너 중복목록 추출
        $designer = $this->excelDesigner($rows);

        // 2. 샵소속 디자이너 목록
        $shopDesigner = $this->shopDesigners($shop_id);
        foreach($shopDesigner as $item) {
            $key = $item->display_name;
            if(isset($designer[$key])) {
                $designer[$key] = false;
            }
        }

        return $designer;
    }

    private function excelDesigner($rows)
    {
        $desiner = [];
        foreach($rows as $row) {
            $row[2]?$name = $row[2]:$name="이름없음";
            $desiner[$name] = true;
        }
        return $desiner;
    }

    private function shopDesigners($shop_id)
    {
        // 샵소속 디자이너 목록
        $ids = $this->shopDesignerIds($shop_id);

        $shopDesigner = DB::table('xe_quantum_designer')->whereIn('user_id', $ids)->get();
        return $shopDesigner;
    }

    private function shopDesignerIds($shop_id)
    {
        $deginers = DB::table('xe_quantum_designer_in_shop')->where('shop_id', $shop_id)->get();
        $ids = [];
        foreach($deginers as $item) {
            $ids []= $item->designer_id;
        }

        return $ids;
    }

    public function shopListAll()
    {
        return DB::table('xe_quantum_shop')->get();
    }

    public function shopInfo($shop_id)
    {
        return DB::table('xe_quantum_shop')->where('shop_id', $shop_id)->first();
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

    public function createUser($rows)
    {
        $default = [
            'email'=>"-----"
        ];

        $max = DB::table('xe_user')->count();
        $users = [];
        foreach($rows as $row) {
            $user = [
                'id' => $this->getKeyGen(),
                'display_name' => $row['display_name'],
                'login_id'=> $max++,
                'password'=> "a",
                'rating'=> "a",
                'status'=> "a",
                'introduction'=> "a",
                'created_at'=> date("Y-m-d H:i:s"),
                'updated_at'=> date("Y-m-d H:i:s")
            ];

            if(isset($row['email'])) $user['email'] = $row['email']; else $user['email'] = $default['email'];

            $users []= $user;
        }

        return $users;
    }

    public function insertUser($designer)
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

    public function createCustomer($rows)
    {
        $customers = [];
        foreach($rows as $row) {

            $customers []= [
                'customer_id' => $this->getKeyGen(),
                'user_id' => NULL,
                'name' => $row['display_name'],
                'phone_number' => $row['mobile'],
                'created_at'=> date("Y-m-d H:i:s"),
                'updated_at'=> date("Y-m-d H:i:s"),
                'customer_memo' => $row['memo']
            ];

        }
        return $customers;

    }

}
