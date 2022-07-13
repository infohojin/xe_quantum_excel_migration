<table>
    @foreach($rows as $row)
    <tr>
        <td>
            <a href='/back-office/shop/migrate/{{$row->shop_id}}'>{{$row->shop_name}}</a>
        </td>
    </tr>
    @endforeach
</table>
