<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Upload extends Controller
{
    private $folder = "import";

    public function index(Request $request)
    {
        $path = storage_path('app/'.$this->folder);
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

        if(!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $dir = [];
        foreach( scandir($path) as $item) {
            if($item == "." || $item == "..") continue;
            $dir []= $item;
        }

        return view("excel::upload",['path'=>$path, 'dir'=>$dir, 'id'=>$id]);

    }


    public function upload(Request $request)
    {
        // $content = $request->file("upload")->get();
        if ($request->hasFile('upload')) {
            $file = $request->file("upload");
            $path = $this->folder;
            if($request->id) {
                $path .= "/".$request->id;
            }
            $file->storeAs($path, $file->getClientOriginalName());

            $fileOriginName = $file->getClientOriginalName();
            $name = substr($fileOriginName,0,strrpos($fileOriginName,"."));
            //return redirect('/excel/view/'.$name);
        }

        return back();
    }

    public function view(Request $request)
    {
        if(isset($request->name) && $request->name){

            $path = storage_path('app/'.$this->folder).DIRECTORY_SEPARATOR;
            if(isset($request->id) && $request->id) {
                $path .= $request->id.DIRECTORY_SEPARATOR;
            }

            $inputFileName = $path.$request->name;
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


    public function convert(Request $request)
    {
        $Excel = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Excel();
        if($inputFileName = $Excel->fileNameCheck($request->name)) {
            // 엑셀파일 읽기
            $datas = $Excel->load($inputFileName);

            $title = $datas[1];
            $rows = [];
            for($i=2; $i<count($datas); $i++) {
                $rows []= $datas[$i];
            }
            unset($datas);

            $Convert = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Convert();
            $sql = $Convert->build($rows);
        }

        return view("excel::convert",['sql'=>$sql]);
    }

    public function success()
    {
    }
}
