@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="Caseworkers">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                @if(isset($caseworkers))
                <tbody>
                    @foreach($caseworkers as $cw)
                        <tr>
                            <td>{{ $cw->id }}</td>
                            <td>{{ $cw->fname }} {{ $cw->lname }}</td>
                            <!-- Add more fields as needed -->
                        </tr>
                    @endforeach
                </tbody>
                @endif
            </table>
        </div>
    </div>
</section>
@endsection