<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Upload extends Controller
{
    private $folder = "import";
    private $migrate;

    public function __construct()
    {
        $this->migrate = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Migrate();
    }

    public function index(Request $request)
    {
        // 숍uuid 지정시 파일 업로드 및 목록 표시
        if($request->uuid) {
            $shop = $this->migrate->shopInfo($request->uuid); // 숍DB 정보

            $path = $this->path($request->uuid);
            $dir = $this->scanShopDirectory($path); // 숍 Excel 파일 목록

            return view("excel::upload",['shop'=>$shop, 'path'=>$path, 'dir'=>$dir]);
        }

        // 숍 목록 출력 및 선택
        $rows = $this->migrate->shopListAll();
        return view("excel::shoplist",['rows'=>$rows]);
    }

    private function scanShopDirectory($path)
    {
        // 숍 import 디렉터리 생성
        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $dir = [];
        foreach( scandir($path) as $item) {
            if($item == "." || $item == "..") continue;
            $dir []= $item;
        }

        return $dir;
    }


    public function upload(Request $request)
    {
        $rules = [
            'upload'=>"customer.xlsx", // 고객데이터
            'upload2'=>"sales.xlsx" // 매출데이터
        ];

        foreach($rules as $formname => $filename) {
            if ($request->hasFile($formname)) {
                $file = $request->file($formname);
                $path = $this->folder."/".$request->shop_id;

                $fileOriginName = $file->getClientOriginalName();
                $fileOriginName = $filename;
                $file->storeAs($path, $fileOriginName);
                $name = substr($fileOriginName,0,strrpos($fileOriginName,"."));
            }
        }

        return back();
    }

    private function path($shop_id=null)
    {
        if($shop_id) {
            return storage_path('app/'.$this->folder)."/".$shop_id;
        }

        return storage_path('app/'.$this->folder);
    }

    private function excelFileName($inputFileName)
    {
        if(file_exists($inputFileName.".xlsx")) {
            $inputFileName .= ".xlsx";
            return $inputFileName;
        } else if(file_exists($inputFileName.".xls")) {
            $inputFileName .= ".xls";
            return $inputFileName;
        } else if(file_exists($inputFileName.".csv")) {

        } else {
            //return "처리 불가능한 파일 포맷입니다.";
        }
    }

    public function view(Request $request)
    {
        if(isset($request->name) && $request->name){

            $path = $this->path().DIRECTORY_SEPARATOR;
            if(isset($request->uuid) && $request->uuid) {
                $path .= $request->uuid.DIRECTORY_SEPARATOR;
            }

            $inputFileName = $this->excelFileName($path.$request->name);
            if($inputFileName && file_exists($inputFileName)) {
                // 엑셀파일 읽기, 전처리 작업
                $excel = (new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel())->load($inputFileName);

                $title = $excel->title(); // 타이틀 분리
                $rows = $excel->get();

                return view("excel::preview",['title'=>$title, 'rows'=>$rows, 'name'=>$request->name]);
            } else {
                return $inputFileName."지정한 파일이 없습니다.";
            }

        } else {
            return "파일명이 선택되지 않았습니다.";
        }
    }

}
