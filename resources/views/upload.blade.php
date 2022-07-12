<h1>Excel Upload Parser</h1>

<ul>
    @foreach($dir as $item)
    <li><a href="/excel/view/{{substr($item,0,strrpos($item,"."))}}">{{$item}}</a></li>
    @endforeach
</ul>



<form action="/excel" method="post" enctype="multipart/form-data">
    <label for="">
        업로드 파일
    </label>
    <input type="file" name="upload" />

    <input type="submit" value="OK">
</form>
