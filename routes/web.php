<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$prefix = "/excel";

// 엑셀목록 및 업로드
//Route::get($prefix.'/{id?}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);




Route::post($prefix.'/convert/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"convert"]);

Route::get($prefix.'/success',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"success"]);

// 샵목록
//Route::get('/back-office/shop/migrate',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::get('/back-office/shop/migrate/{uuid?}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::post('/back-office/shop/migrate/{uuid}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"upload"]);
Route::get('/back-office/shop/migrate/{uuid}/view/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"view"]);


Route::get('/back-office/shop/migrate/{uuid}/preparatory',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Preparatory::class,"index"]);
