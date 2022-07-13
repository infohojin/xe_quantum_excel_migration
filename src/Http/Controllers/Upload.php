<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Upload extends Controller
{
    private $folder = "import";

    public function index(Request $request)
    {
        if($request->uuid) {
            $shop = DB::table('xe_quantum_shop')->where('shop_id', $request->uuid)->first();

            $path = storage_path('app/'.$this->folder)."/".$request->uuid;
            if(!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $dir = [];

            foreach( scandir($path) as $item) {
                if($item == "." || $item == "..") continue;
                $dir []= $item;
            }
            return view("excel::upload",['shop'=>$shop, 'path'=>$path, 'dir'=>$dir]);
        }

        $rows = DB::table('xe_quantum_shop')->get();
        return view("excel::shoplist",['rows'=>$rows]);
    }


    public function upload(Request $request)
    {
        // $content = $request->file("upload")->get();
        // 고객데이터
        if ($request->hasFile('upload')) {
            $file = $request->file("upload");
            $path = $this->folder."/".$request->shop_id;

            $fileOriginName = $file->getClientOriginalName();
            $fileOriginName = "customer.xlsx";
            $file->storeAs($path, $fileOriginName);
            $name = substr($fileOriginName,0,strrpos($fileOriginName,"."));
        }

        // 매출데이터
        if ($request->hasFile('upload2')) {
            $file = $request->file("upload2");
            $path = $this->folder."/".$request->shop_id;

            $fileOriginName = $file->getClientOriginalName();
            $fileOriginName = "sales.xlsx";
            $file->storeAs($path, $fileOriginName);
            $name = substr($fileOriginName,0,strrpos($fileOriginName,"."));
        }

        return back();
    }

    public function view(Request $request)
    {
        if(isset($request->name) && $request->name){

            $path = storage_path('app/'.$this->folder).DIRECTORY_SEPARATOR;
            if(isset($request->uuid) && $request->uuid) {
                $path .= $request->uuid.DIRECTORY_SEPARATOR;
            }

            $inputFileName = $path.$request->name;
            //dd($inputFileName);
            if(file_exists($inputFileName.".xlsx")) {
                $inputFileName .= ".xlsx";

            } else if(file_exists($inputFileName.".xls")) {
                $inputFileName .= ".xls";

            } else if(file_exists($inputFileName.".csv")) {

            } else {
                //return "처리 불가능한 파일 포맷입니다.";
            }

            if(file_exists($inputFileName)) {
                // 엑셀파일 읽기
                $Excel = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel();
                $datas = $Excel->load($inputFileName);
                $title = $datas[1]; // 타이틀
                $rows = [];
                for($i=2; $i<count($datas); $i++) {
                    $rows []= $datas[$i];
                }
                unset($datas);
                return view("excel::preview",['title'=>$title, 'rows'=>$rows, 'name'=>$request->name]);
            } else {
                return $inputFileName."지정한 파일이 없습니다.";
            }

        } else {
            return "파일명이 선택되지 않았습니다.";
        }
    }


    /*
    public function index(Request $request)
    {

        if($request->id) {
            if(is_numeric($request->id)) {
                $path .= "/".$request->id;
                $id = $request->id;
            } else {
                return "id값 지정은 숫자만 가능합니다.";
            }
        } else {
            $id = 0;
        }





        return view("excel::upload",['path'=>$path, 'dir'=>$dir, 'id'=>$id]);

    }









    public function success()
    {
    }
    */

}
