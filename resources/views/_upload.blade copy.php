<h1>Excel Upload Parser</h1>


<ul>
    @foreach($dir as $item)
    @if(is_dir($path."/".$item))
    <li><a href="/excel/{{$item}}">{{$item}}</a></li>
    @else
    <li><a href="/excel/view/{{$id}}/{{substr($item,0,strrpos($item,"."))}}">{{$item}}</a></li>
    @endif
    @endforeach
</ul>


<form action="/excel" method="post" enctype="multipart/form-data">
    <select name="brand">
        <option value="1">헤어짱</option>
        <option value="2">핸즈</option>
    </select>

    <label for="">
        업로드 파일
    </label>
    <input type="hidden" name="id" value="{{$id}}"/>
    <input type="file" name="upload" />

    <input type="submit" value="OK">
</form>
