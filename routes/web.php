<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 샵목록
//Route::get('/back-office/shop/migrate',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::get('/back-office/shop/migrate/{uuid?}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"index"]);
Route::post('/back-office/shop/migrate/{uuid}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"upload"]);
Route::get('/back-office/shop/migrate/{uuid}/view/{name}',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Upload::class,"view"]);

// 변환작업
Route::get('/back-office/shop/migrate/{uuid}/preview',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Preview::class,"index"]);

// 변환작업
Route::get('/back-office/shop/migrate/{uuid}/sales',[\XEHub\XePlugin\CustomQuantum\Excel\Http\Controllers\Preview::class,"sales"]);
