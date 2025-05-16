@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            align-items: center;
        }
        .form-group {
            flex: 1 1 160px;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group-title {
            flex: 1 1 90px;
            color: gray;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .status-group {
            display: flex;
            align-items: center;
            flex: 1 1 200px;
        }
        .status-group select {
            background-color: #e7f0fa; /* Light blue background */
        }
    </style>

    <style>
        .container {
            display: flex;
            gap: 20px;
            margin-top: 50px
        }

        .column {
            flex: 3;
            min-width: 200px;
        }

        .form-column {
            flex: 7;
            min-width: 360px;
        }

        table {
            width: 100%;
            border-spacing: 0 10px;
        }

        .table-header {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        td {
            padding: 5px 0;
            border-bottom: 1px solid #ccc;
        }

        .form-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .remarks {
            display: flex;
            gap: 20px;
        }

        .remarks div {
            flex: 1;
        }

        textarea {
            width: 100%;
            height: 60px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(19px);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .info {
            font-size: 14px;
            color: gray;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cancel {
            background-color: #ddd;
            color: black;
            text-decoration: underline;
        }

        .btn-save {
            background-color: #007bff;
            color: white;
        }
    </style>
@endsection

@section('content')
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="POST" action="{{ isset($ttu) ? route('admin.ttus.update', $ttu->id) : route('admin.ttus.store') }}">
                @csrf
                @if(isset($ttu))
                    @method('PUT')
                @endif

                @if(isset($ttu))
                    <input type="hidden" name="created_at" value="{{ $ttu->created_at }}">
                @endif
                <input type="hidden" name="updated_at" value="{{ now() }}">

                <!-- Main TTU Info -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="vin">VIN</label>
                        <input type="text" id="vin" name="vin" value="{{ old('vin', $ttu->vin ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer</label>
                        <select id="manufacturer" name="manufacturer">
                            <option {{ old('manufacturer', $ttu->manufacturer ?? '') == 'Forest River' ? 'selected' : '' }}>Forest River</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <select id="brand" name="brand">
                            <option {{ old('brand', $ttu->brand ?? '') == 'Apex Ultra Lite' ? 'selected' : '' }}>Apex Ultra Lite</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" value="{{ old('model', $ttu->model ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" id="year" name="year" value="{{ old('year', $ttu->year ?? '') }}">
                    </div>
                    <div class="form-group status-group">
                        <label for="status">TTU Status</label>
                        <select id="status" name="status">
                            <option {{ old('status', $ttu->status ?? '') == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                            <option {{ old('status', $ttu->status ?? '') == 'Ready' ? 'selected' : '' }}>Ready</option>
                            <option {{ old('status', $ttu->status ?? '') == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                        </select>
                    </div>
                </div>

                <!-- Title Info -->
                <div class="form-row">
                    <div class="form-group form-group-title">Title Manufacturer, Brand, Model:</div>
                    <div class="form-group">
                        <input type="text" name="title_manufacturer" placeholder="Enter manufacturer" value="{{ old('title_manufacturer', $ttu->title_manufacturer ?? '') }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="title_brand" placeholder="Enter brand" value="{{ old('title_brand', $ttu->title_brand ?? '') }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="title_model" placeholder="Enter model" value="{{ old('title_model', $ttu->title_model ?? '') }}">
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: flex-end;">
                        <label for="title-select" style="margin-right: 5px; font-size: 13px;">Do&nbsp;we&nbsp;have&nbsp;the&nbsp;Title?</label>
                        <select id="title-select" name="has_title">
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <!-- Location & Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $ttu->location ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="lot">Unit Loc./Lot #</label>
                        <input type="text" id="lot" name="lot" value="{{ old('lot', $ttu->lot ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="county">County</label>
                        <select id="county" name="county">
                            <option {{ old('county', $ttu->county ?? '') == 'Brexit County' ? 'selected' : '' }}>Brexit County</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="imei">IMEI# (GPS)</label>
                        <input type="text" id="imei" name="imei" value="{{ old('imei', $ttu->imei ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="price">Purchase Price</label>
                        <input type="text" id="price" name="price" value="{{ old('price', $ttu->price ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $ttu->address ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="total_beds">Total beds</label>
                        <input type="number" id="total_beds" name="total_beds" value="{{ old('total_beds', $ttu->total_beds ?? '') }}">
                    </div>
                </div>

                <!-- Features and Statuses -->
                <div class="container">
                    <div class="column">
                        <span class="table-header">Does the TTU have:</span>
                        <table>
                            @php
                                $featureList = ['200+ SQFT', 'Prop. Fireplace', 'TV', 'Hydraulics', 'Steps', 'Teardrop Design', 'Folding Walls', 'Outdoor Kitchen'];
                            @endphp
                            @foreach($featureList as $feature)
                            <tr>
                                <td>{{ $feature }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="features[{{ $feature }}]" {{ old("features.$feature", !empty($features[$feature])) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="column">
                        <span class="table-header">Is the TTU:</span>
                        <table>
                            @php
                                $statusList = ['Onsite', 'Occupied', 'Winterized', 'Deblocked', 'Cleaned', 'GPS Removed', 'Being Donated', 'Sold at Auction'];
                            @endphp
                            @foreach($statusList as $status)
                            <tr>
                                <td>{{ $status }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="statuses[{{ $status }}]" {{ old("statuses.$status", !empty($statuses[$status])) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="form-column form-section">
                        <label for="disposition">Disposition:</label>
                        <select id="disposition" name="disposition">
                            <option {{ old('disposition', $ttu->disposition ?? '') == 'Officially Transferred' ? 'selected' : '' }}>Officially Transferred</option>
                        </select>
                        <label for="transport">Transport Agency:</label>
                        <select id="transport" name="transport">
                            <option {{ old('transport', $ttu->transport ?? '') == 'Select...' ? 'selected' : '' }}>Select...</option>
                        </select>
                        <div class="status-group">
                            <label>Is the recipient a State, City, County, or NPO?</label>
                            <select name="recipient_type">
                                <option {{ old('recipient_type', $ttu->recipient_type ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                                <option {{ old('recipient_type', $ttu->recipient_type ?? '') == 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                        <label for="agency">What Agency Is being given to?</label>
                        <input type="text" id="agency" name="agency" value="{{ old('agency', $ttu->agency ?? '') }}">
                        <label for="category">Donation Category:</label>
                        <input type="text" id="category" name="category" value="{{ old('category', $ttu->category ?? '') }}">
                        <div class="remarks">
                            <div>
                                <label for="remarks">Remarks:</label>
                                <textarea id="remarks" name="remarks">{{ old('remarks', $ttu->remarks ?? '') }}</textarea>
                            </div>
                            <div>
                                <label for="comments">Comments/Notes:</label>
                                <textarea id="comments" name="comments">{{ old('comments', $ttu->comments ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Survivor -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4>Assigned Survivor</h4>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fema">FEMA ID</label>
                                <input type="text" id="fema" name="fema" value="{{ old('fema', $ttu->fema ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="survivor_name" value="{{ old('survivor_name', $ttu->survivor_name ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="lo">LO</label>
                                <select id="lo" name="lo">
                                    <option {{ old('lo', $ttu->lo ?? '') == 'NO' ? 'selected' : '' }}>NO</option>
                                    <option {{ old('lo', $ttu->lo ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lo-date">LO Date</label>
                                <input type="text" id="lo-date" name="lo_date" value="{{ old('lo_date', $ttu->lo_date ?? 'N/A') }}">
                            </div>
                            <div class="form-group">
                                <label for="est-lo-date">Est. LO Date</label>
                                <input type="text" id="est-lo-date" name="est_lo_date" value="{{ old('est_lo_date', $ttu->est_lo_date ?? 'N/A') }}">
                            </div>
                        </div>

                        <div class="form-footer">
                            <div class="info">
                                <div>Authored by:</div>
                                @if(isset($ttu))
                                    <div style="display: flex; gap: 40px;">
                                        <span>Created: {{ $ttu->created_at }}</span>
                                        <span>Last Edited: {{ $ttu->updated_at }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="buttons">
                                <button type="button" class="btn btn-cancel" onclick="window.history.back();">Cancel</button>
                                <button type="submit" class="btn btn-save">{{ isset($ttu) ? 'Update' : 'Save' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
        document.querySelectorAll('.options-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const dropdown = this.querySelector('.dropdown-menu');
                const isActive = dropdown.classList.contains('active');
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                if (!isActive) {
                    dropdown.classList.add('active');
                }
            });
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.options-icon')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
            }
        });
</script>
@endsection
