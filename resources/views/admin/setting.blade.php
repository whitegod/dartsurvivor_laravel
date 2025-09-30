@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
    <div class="col-md-3 col-sm-4 col-xs-6">
    <h3>FDEC #'s</h3>
    @if(isset($fdecs) && is_iterable($fdecs))
        <div class="fdec-table-wrapper">
        <table class="table">
            <tbody>
                @foreach($fdecs as $f)
                <tr>
                    <td>{{ $f->fdec_no ?? $f->name ?? json_encode($f) }}</td>
                    <td>
                        <form method="POST" action="{{ url('/admin/setting/delete/' . ($f->id ?? '')) }}" onsubmit="return confirm('Delete this FDEC entry?');" style="display:inline;">
                            @csrf
                            <button type="submit" title="Delete" style="background: transparent; border: none; color: #c0392b; font-size: 16px; cursor: pointer;">✖</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">
            <form method="POST" action="{{ route('admin.setting.store') }}" class="fdec-add-form">
                @csrf
                <input type="text" name="fdec" placeholder="New FDEC #" required>
                <button type="submit" class="add-new-button">Add New</button>
            </form>
        </div>
        </div>
    @endif
@endsection