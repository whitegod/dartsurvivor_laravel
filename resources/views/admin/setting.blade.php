@extends('template.template')

@section('content')
    <h1>FDEC Table</h1>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                @foreach($fdecs->first() ? array_keys(get_object_vars($fdecs->first())) : [] as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($fdecs as $fdec)
                <tr>
                    @foreach(get_object_vars($fdec) as $val)
                        <td>{{ $val }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="100%">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection