<?php
// XEHub\XePlugin\CustomQuantum
namespace XEHub\XePlugin\CustomQuantum\Excel\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Excel
{
    private $rows;

    public function load($filename)
    {
        // 엑셀파일 읽기
        $objExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
        $objExcel->setActiveSheetIndex(0); // 첫번째 시트를 선택
        $objWorksheet = $objExcel->getActiveSheet();

        $this->rows = $objWorksheet->toArray();
        //return $objWorksheet->toArray();
        return $this;
    }

    public function get($start=2)
    {
        $rows = [];
        for($i=$start; $i<count($this->rows); $i++) {
            $rows []= $this->rows[$i];
        }

        return $rows;
    }

    public function title($line=1)
    {
        // 엑셀 타이틀
        return $this->rows[$line];
    }



    /*
    public function fileNameCheck($name)
    {
        $inputFileName = storage_path('app/uploads').DIRECTORY_SEPARATOR.$name;
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

        return false;
    }
    */



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
