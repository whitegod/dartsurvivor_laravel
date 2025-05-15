@extends('template.template')

@section('content-header')
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
@endsection

@section('content')
<section id="EditSurvivor">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2>{{ isset($survivor) ? 'Edit Survivor' : 'Add New Survivor' }}</h2>
            <form method="POST" action="{{ isset($survivor) ? route('admin.survivors.update', $survivor->id) : route('admin.survivors.store') }}">
                @csrf
                @if(isset($survivor))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="fema_id">FEMA-ID:</label>
                    <input type="text" id="fema_id" name="fema_id" class="form-control" value="{{ $survivor->fema_id ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $survivor->name ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ $survivor->address ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $survivor->phone ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="hh_size">HH Size:</label>
                    <input type="number" id="hh_size" name="hh_size" class="form-control" value="{{ $survivor->hh_size ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="li_date">LI Date:</label>
                    <input type="date" id="li_date" name="li_date" class="form-control" value="{{ $survivor->li_date ?? '' }}" required>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($survivor) ? 'Update' : 'Save' }}</button>
            </form>
        </div>
    </div>
</section>
@endsection