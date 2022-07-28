# EXCEL Migration
라라벨 공식 패키지 생성으로 모듈화 되어 개발 하였습니다.

## 설치
0. 라이브러리 컴포저 설치
```
composer require maatwebsite/excel
```

2. 폴더 복사

3. 서비스프로바이더 등록
```
XEHub\XePlugin\CustomQuantum\Excel\ExcelServiceProvider::class,
```

4.컴포저 네임스페이스 등록
```
"XEHub\\XePlugin\\CustomQuantum\\Excel\\": "XEHub/XePlugin/CustomQuantum/excel/src"
```

헬퍼파일 등록
```
"files": [
            "XeHub/XePlugin/CustomQuantum/excel/src/Helpers/Helper.php"
        ]
```
