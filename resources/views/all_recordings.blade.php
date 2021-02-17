<html>
<body>
<h1>Recordings</h1>

@foreach ($recordings as $recording)
    <h3> {{ $recording->title }}</h3>
    <p>length: {{ $recording->get_length_min()}} min</p>
    <p>
        @if ($recording->ISRC != '')
            ISRC: {{ $recording->ISRC }},
        @endif

        @if ($recording->MBID != '')
            MBID: {{ $recording->MBID }}</p>
    @endif
@endforeach

</body>
</html>
