<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Preview extends Controller
{
    private $folder = "import";


    public function index(Request $request)
    {
        $inputFileName = storage_path('app/'.$this->folder).DIRECTORY_SEPARATOR.$request->uuid;
        $inputFileName .= DIRECTORY_SEPARATOR."customer.xlsx";

        if(file_exists($inputFileName)) {
            // 엑셀파일 읽기
            $excel = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel();
            $excel->load($inputFileName);

            $title = $excel->title();
            $rows = $excel->get();

            // =====
            $migrate = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Migrate();

            // 엑셀 디자이너와 DB 디자이너 명단 중복 체크
            $designer = $migrate->checkDeginers($rows, $request->uuid);
            //==> $users = $migrate->insertUser($designer);
            //==>  $migrate->insertDesginer($users, $request->uuid);

            $customers = [];
            foreach($rows as $row) {
                $customers []= [
                    'display_name'=>$row[0], //성명
                    'sex'=>$row[1], //성별
                    'manager'=>$row[2], //담당자
                    'birth'=>$row[3], //생년월일
                    'birth_type'=>$row[4], // 양력/음력
                    'mobile'=>$row[5], //핸드폰
                    'post'=>$row[6], //우편번호
                    'address'=>$row[7], //주소
                    'memo'=>$row[21] // 고객메모
                ];
            }

            // $customerUsers = $migrate->createUser($customers); // 회원 가입은 안함
            $customerUsers = $migrate->createCustomer($customers); // 데이터 array
            dd($customerUsers);
            //DB::table('xe_quantum_customer')->insert($customerUsers);





            return view("excel::preview",['designer'=>$designer]);
        }

        return "customer.xlsx 파일이 없습니다.";
    }

    public function sales(Request $request)
    {
        $inputFileName = storage_path('app/'.$this->folder).DIRECTORY_SEPARATOR.$request->uuid;
        $inputFileName .= DIRECTORY_SEPARATOR."sales.xlsx";

        if(file_exists($inputFileName)) {
            // 엑셀파일 읽기
            $excel = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel();
            $excel->load($inputFileName);

            $title = $excel->title($line=21);
            $rows = $excel->get($line=22);

            dd($title);
            dd($rows);

            // =====
            $migrate = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Migrate();

            // 엑셀 디자이너와 DB 디자이너 명단 중복 체크
            $designer = $migrate->checkDeginers($rows, $request->uuid);
            //==> $users = $migrate->insertUser($designer);
            //==>  $migrate->insertDesginer($users, $request->uuid);

            $customers = [];
            foreach($rows as $row) {
                $customers []= [
                    'display_name'=>$row[0], //성명
                    'sex'=>$row[1], //성별
                    'manager'=>$row[2], //담당자
                    'birth'=>$row[3], //생년월일
                    'birth_type'=>$row[4], // 양력/음력
                    'mobile'=>$row[5], //핸드폰
                    'post'=>$row[6], //우편번호
                    'address'=>$row[7], //주소
                    'memo'=>$row[21] // 고객메모
                ];
            }

            // $customerUsers = $migrate->createUser($customers); // 회원 가입은 안함
            $customerUsers = $migrate->createCustomer($customers); // 데이터 array
            dd($customerUsers);
            //DB::table('xe_quantum_customer')->insert($customerUsers);





            return view("excel::preview",['designer'=>$designer]);
        }

        return "customer.xlsx 파일이 없습니다.";
    }




}
