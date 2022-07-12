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
        $path = storage_path('app/'.$this->folder);

        $dir = [];
        foreach( scandir($path) as $item) {
            if($item == "." || $item == "..") continue;
            $dir []= $item;
        }

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
        $inputFileName = storage_path('app/uploads').DIRECTORY_SEPARATOR.$request->name;
        if(file_exists($inputFileName.".xlsx")) {
            $inputFileName .= ".xlsx";
        } else if(file_exists($inputFileName.".xls")) {
            $inputFileName .= ".xls";
        } else if(file_exists($inputFileName.".csv")) {

        } else {
            return "처리 불가능한 파일 포맷입니다.";
        }


        // 엑셀파일 읽기
        $objExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $objExcel->setActiveSheetIndex(0); // 첫번째 시트를 선택
        $objWorksheet = $objExcel->getActiveSheet();

        $datas = $objWorksheet->toArray();
        $title = $datas[1];
        $rows = [];
        for($i=2; $i<count($datas); $i++) {
            $rows []= $datas[$i];
        }
        unset($datas);

        return view("excel::preview",['title'=>$title, 'rows'=>$rows, 'name'=>$request->name]);


        /*
        $rowIterator = $objWorksheet->getRowIterator();
        //dd($rowIterator);

        foreach ($rowIterator as $row) {
            // 모든 행에 대해서
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
        }

        $maxRow = $objWorksheet->getHighestRow();
        for ($i = 0 ; $i <= $maxRow ; $i++) {
            $dataA = $objWorksheet->getCell('A' . $i)->getValue(); // A열
            echo $dataA;
            $dataB = $objWorksheet->getCell('B' . $i)->getValue(); // B열
            echo $dataB;
            $dataC = $objWorksheet->getCell('C' . $i)->getValue(); // C열
            echo $dataC;
            $dataD = $objWorksheet->getCell('D' . $i)->getValue(); // D열
            echo $dataD;
            //$dataE = $objWorksheet->getCell('E' . $i)->getValue(); // E열
            //$dataF = $objWorksheet->getCell('F' . $i)->getValue(); // F열

            // 날짜 형태의 셀을 읽을때는 toFormattedString를 사용
            //$dataF = PHPExcel_Style_NumberFormat::toFormattedString($dataF, 'YYYY-MM-DD'); 
        }
        */


    }

    public function convert(Request $request)
    {
        $inputFileName = storage_path('app/uploads').DIRECTORY_SEPARATOR.$request->name;
        if(file_exists($inputFileName.".xlsx")) {
            $inputFileName .= ".xlsx";
        } else if(file_exists($inputFileName.".xls")) {
            $inputFileName .= ".xls";
        } else if(file_exists($inputFileName.".csv")) {

        } else {
            return "처리 불가능한 파일 포맷입니다.";
        }

        // 엑셀파일 읽기
        $objExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $objExcel->setActiveSheetIndex(0); // 첫번째 시트를 선택
        $objWorksheet = $objExcel->getActiveSheet();

        $datas = $objWorksheet->toArray();
        $title = $datas[1];
        $rows = [];
        for($i=2; $i<count($datas); $i++) {
            $rows []= $datas[$i];
        }
        unset($datas);

        $sql = [];
        foreach($rows as $row) {
            $query = "insert ";

            foreach($row as $key => $value) {
                $query .= "`".$key."`='".$value."' ";
            }

            $query .= ";";
            $sql []= $query;
        }

        return view("excel::convert",['sql'=>$sql]);
    }

    public function success()
    {
    }
}
