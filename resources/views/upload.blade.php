<h1>{{$shop->shop_name}}</h1>

@if(empty($dir))
<div>
    등록된 마이그레이션 엑셀.xlsx 파일이 없습니다.
</div>
@else
<ul>
    @foreach($dir as $item)
    <li>
        <a href="/back-office/shop/migrate/{{$shop->shop_id}}/view/{{substr($item,0,strrpos($item,"."))}}">{{$item}}</a>
    </li>
    @endforeach
</ul>
@endif


<form action="/back-office/shop/migrate/{{$shop->shop_id}}" method="post" enctype="multipart/form-data">
    <select name="brand">
        <option value="1">헤어짱</option>
        <option value="2">핸즈</option>
    </select>

    <input type="hidden" name="shop_id" value="{{$shop->shop_id}}"/>

    <label for="">
        고객명단
    </label>
    <input type="file" name="upload" />

    <label for="">
        매출자료
    </label>
    <input type="file" name="upload2" />

    <input type="submit" value="OK">
</form>


<a href='/back-office/shop/migrate/{{$shop->shop_id}}/preview'>Preview customer</a>
<a href='/back-office/shop/migrate/{{$shop->shop_id}}/sales'>Preview sales</a>
