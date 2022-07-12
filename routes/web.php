<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$prefix = "/excel";

// 엑셀목록 및 업로드
Route::get($prefix,[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::post($prefix,[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"upload"]);

Route::get($prefix.'/view/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"view"]);

Route::post($prefix.'/convert/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"convert"]);

Route::get($prefix.'/success',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"success"]);
