<h1>미리보기</h1>
<form action="">

    <input type="submit" value="Convert">

    <table>
            <tr>
                @foreach($title as $i=>$item)
                    <td>{{$item}}</td>
                @endforeach
            </tr>
            <tr>
                @foreach($title as $i=>$item)
                    <td><input type="text" name="col{{$i}}"/></td>
                @endforeach
            </tr>

            @foreach ($rows as $row)
            <tr>
                @foreach($row as $item)
                <td>{{$item}}</td>
                @endforeach
            </tr>
            @endforeach
    </table>
</form>
