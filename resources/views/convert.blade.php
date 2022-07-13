<h1>디자이너 변환.</h1>
<br/>

<ul>
    @foreach($designer as $name => $status)
    <li>
        @if($status)
            <div>{{$name}}</div>
        @else
            <div style="text-decoration:line-through">{{$name}}</div>
        @endif
    </li>
    @endforeach
</ul>

<button>디자이너 추가</button>
