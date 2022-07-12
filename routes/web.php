<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



// 엑셀목록 및 업로드
Route::get('/excel',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::post('/excel',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"upload"]);

Route::get('/excel/view/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"view"]);

Route::get('/excel/success',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"success"]);
