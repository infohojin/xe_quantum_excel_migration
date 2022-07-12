<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Upload extends Controller
{
    private $folder = "uploads";

    public function index()
    {
        $Convert = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Convert();
        $dir = $Convert->fileList($this->folder);

        return view("excel::upload",['dir'=>$dir]);
    }


    public function upload(Request $request)
    {
        // $content = $request->file("upload")->get();
        if ($request->hasFile('upload')) {
            $file = $request->file("upload");
            $file->storeAs('uploads', $file->getClientOriginalName());

            $fileOriginName = $file->getClientOriginalName();
            $name = substr($fileOriginName,0,strrpos($fileOriginName,"."));
            return redirect('/excel/view/'.$name);
        }

        return back();
    }

    public function view(Request $request)
    {
        $Convert = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Convert();
        if($inputFileName = $Convert->fileNameCheck($request->name)) {
            // 엑셀파일 읽기
            $datas = $Convert->load($inputFileName);
            $title = $datas[1]; // 타이틀
            $rows = [];
            for($i=2; $i<count($datas); $i++) {
                $rows []= $datas[$i];
            }
            unset($datas);
        }

        return view("excel::preview",['title'=>$title, 'rows'=>$rows, 'name'=>$request->name]);
    }


    public function convert(Request $request)
    {
        $Convert = new \XEHub\XePlugin\CustomQuantum\Excel\Http\Convert();
        if($inputFileName = $Convert->fileNameCheck($request->name)) {
            // 엑셀파일 읽기
            $datas = $Convert->load($inputFileName);

            $title = $datas[1];
            $rows = [];
            for($i=2; $i<count($datas); $i++) {
                $rows []= $datas[$i];
            }
            unset($datas);

            $sql = $Convert->buildInsert($rows);
        }

        return view("excel::convert",['sql'=>$sql]);
    }

    public function success()
    {
    }
}
