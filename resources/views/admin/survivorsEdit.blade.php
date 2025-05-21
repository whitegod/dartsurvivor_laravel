@extends('template.template')

@section('content-header')
    <style>
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1 1 160px;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 6px;
            display: block;
        }
        input[type="text"], input[type="number"], input[type="email"], input[type="date"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            /* font-size: 14px; */
            font-family: inherit;
            font-weight: 400;
        }
        textarea {
            width: 100%;
            height: 120px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }
        select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #f9f9f9;
            /* font-size: 14px; */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            font-family: inherit;
            font-weight: 400;
        }
        select {
            background-image: url("data:image/svg+xml;charset=UTF-8,<svg width='16' height='16' fill='gray' xmlns='http://www.w3.org/2000/svg'><path d='M4 6l4 4 4-4'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }
        .info {
            font-size: 14px;
            color: gray;
        }
        /* Custom square radio buttons styled as checkboxes */
        input[type="radio"] {
            margin: 0;
        }
        .custom-radio-square {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #007bff;
            border-radius: 3px;
            background: #fff;
            cursor: pointer;
            position: relative;
            margin: 0;
            outline: none;
            transition: border-color 0.2s;
        }
        .custom-radio-square:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        .custom-radio-square:checked::after {
            content: '';
            display: block;
            position: absolute;
            left: 4px;
            top: 0px;
            width: 6px;
            height: 12px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) scale(1.1);
        }
    </style>
@endsection

@php
    $locationType = old('location_type', $survivor->location_type ?? 'TTU');
@endphp

@section('content')
<section id="EditSurvivor">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 0 auto;">
            <form method="POST" action="{{ isset($survivor) ? route('admin.survivors.update', $survivor->id) : route('admin.survivors.store') }}">
                @csrf
                @if(isset($survivor))
                    @method('PUT')
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" value="{{ $survivor->fname ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" value="{{ $survivor->lname ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ $survivor->address ?? '' }}">
                    </div>
                    <div class="form-group" style="flex:4;">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ $survivor->city ?? '' }}">
                    </div>
                    <div class="form-group" style="flex:1.2;">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="{{ $survivor->state ?? '' }}">
                    </div>
                    <div class="form-group" style="flex:1.2;">
                        <label for="zip">Zip</label>
                        <input type="text" id="zip" name="zip" value="{{ $survivor->zip ?? '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="primary_phone">Primary Phone</label>
                        <input type="text" id="primary_phone" name="primary_phone" value="{{ $survivor->primary_phone ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="secondary_phone">Secondary Phone</label>
                        <input type="text" id="secondary_phone" name="secondary_phone" value="{{ $survivor->secondary_phone ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" id="county" name="county" value="{{ $survivor->county ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="own_rent">Own/Rent</label>
                        <select id="own_rent" name="own_rent">
                            <option value="Own" {{ (old('own_rent', $survivor->own_rent ?? '') == 'Own') ? 'selected' : '' }}>Own</option>
                            <option value="Rent" {{ (old('own_rent', $survivor->own_rent ?? '') == 'Rent') ? 'selected' : '' }}>Rent</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label class="info" style="margin-bottom: 4px;">Family Information</label>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0; width: 100%; margin-right: 8px;">
                                <label for="hh_size" style="margin: 0 8px 0 0; min-width: 60px; font-weight: normal;">Household Size</label>
                            </div>    
                            <input type="text" id="hh_size" name="hh_size" value="{{ $survivor->hh_size ?? '' }}" style="margin-bottom: 0; width: 40px;">
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <label for="pets" style="margin: 0 8px 0 0; min-width: 60px; font-weight: normal;">Pets</label>
                            <input type="text" id="pets" name="pets" value="{{ $survivor->pets ?? '' }}" style="width: 40px;">
                        </div>
                    </div>
                </div>

                <div class="form-row" style="align-items: end">
                    <div class="form-group" style="min-width:140px; max-width:260px; flex:1;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $survivor->email ?? '' }}">
                    </div>
                    <div class="form-group" style="min-width:220px;">
                        <div style="display: flex; gap: 24px; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                TTU
                                <input type="radio" name="location_type" value="TTU"
                                    {{ $locationType == 'TTU' ? 'checked' : '' }}
                                    class="custom-radio-square" onchange="toggleTTURow()">
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                Hotel
                                <input type="radio" name="location_type" value="Hotel"
                                    {{ $locationType == 'Hotel' ? 'checked' : '' }}
                                    class="custom-radio-square" onchange="toggleTTURow()">
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                State Park
                                <input type="radio" name="location_type" value="State Park"
                                    {{ $locationType == 'State Park' ? 'checked' : '' }}
                                    class="custom-radio-square" onchange="toggleTTURow()">
                            </label>
                        </div>
                    </div>
                </div>

                <div id="ttu-row" style="{{ $locationType == 'TTU' ? '' : 'display:none;' }}; margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned TTU</label>
                    <div class="form-row">
                        <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                            <label class="info" style="margin-bottom:6px;">VIN</label>
                            <div style="display:flex; gap:4px;">
                                <input type="text" name="vin" value="{{ old('vin', $survivor->vin ?? '') }}">
                                <button class="btn btn-primary" type="button">
                                    Go-to Record
                                </button>
                            </div>
                        </div>
                        <div class="form-group" style="flex:0.5; min-width:70px;">
                            <label for="lo">LO</label>
                            <select id="lo" name="lo">
                                <option value="NO" {{ old('lo', $survivor->lo ?? '') == 'NO' ? 'selected' : '' }}>NO</option>
                                <option value="YES" {{ old('lo', $survivor->lo ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lo_date">LO Date</label>
                            <input id="lo_date" name="lo_date" type="date" value="{{ old('lo_date', $survivor->lo_date ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="est_lo_date">Est. LO Date</label>
                            <input id="est_lo_date" name="est_lo_date" type="date" value="{{ old('est_lo_date', $survivor->est_lo_date ?? '') }}">
                        </div>
                    </div>
                </div>

                <div id="hotel-row" style="{{ $locationType == 'Hotel' ? '' : 'display:none;' }}; margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned Hotel</label>
                    <div class="form-row">
                        <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                            <label class="info" style="margin-bottom:6px;">Hotel Name</label>
                            <div style="display:flex; gap:4px;">
                                <input type="text" name="hotel_name" value="{{ old('hotel_name', $survivor->hotel_name ?? '') }}" placeholder="Hotel Name">
                                <input type="text" name="hotel_room" value="{{ old('hotel_room', $survivor->hotel_room ?? '') }}" placeholder="Room #" style="max-width:90px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hotel_li_date">LI Date</label>
                            <input id="hotel_li_date" name="hotel_li_date" type="date" value="{{ old('hotel_li_date', $survivor->hotel_li_date ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="hotel_lo_date">LO Date</label>
                            <input id="hotel_lo_date" name="hotel_lo_date" type="date" value="{{ old('hotel_lo_date', $survivor->hotel_lo_date ?? '') }}">
                        </div>
                    </div>
                </div>

                <div id="statepark-row" style="{{ $locationType == 'State Park' ? '' : 'display:none;' }}; margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned State Park</label>
                    <div class="form-row">
                        <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                            <label class="info" style="margin-bottom:6px;">State Park Name</label>
                            <div style="display:flex; gap:4px;">
                                <input type="text" name="statepark_name" value="{{ old('statepark_name', $survivor->statepark_name ?? '') }}" placeholder="State Park Name">
                                <input type="text" name="statepark_site" value="{{ old('statepark_site', $survivor->statepark_site ?? '') }}" placeholder="Site #" style="max-width:90px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="statepark_li_date">LI Date</label>
                            <input id="statepark_li_date" name="statepark_li_date" type="date" value="{{ old('statepark_li_date', $survivor->statepark_li_date ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="statepark_lo_date">LO Date</label>
                            <input id="statepark_lo_date" name="statepark_lo_date" type="date" value="{{ old('statepark_lo_date', $survivor->statepark_lo_date ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="form-row" style="display: flex; gap: 32px;">
                    <!-- Left Column -->
                    <div style="flex: 1; min-width: 260px; max-width: 340px;">
                        <div class="form-group" style="min-width:120px; max-width:180px;">
                            <label for="opt_out">Opt Out?</label>
                            <select id="opt_out" name="opt_out">
                                <option value="NO" {{ (old('opt_out', $survivor->opt_out ?? '') == 'NO') ? 'selected' : '' }}>NO</option>
                                <option value="YES" {{ (old('opt_out', $survivor->opt_out ?? '') == 'YES') ? 'selected' : '' }}>YES</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 16px;">
                            <div class="form-group" style="min-width:120px; max-width:180px;">
                                <label for="opt_out_reason">Reason</label>
                                <select id="opt_out_reason" name="opt_out_reason">
                                    <option value="N/A" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'N/A') ? 'selected' : '' }}>N/A</option>
                                    <option value="Personal" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'Personal') ? 'selected' : '' }}>Personal</option>
                                    <option value="Other" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group" style="min-width:120px; max-width:180px;">
                                <label for="case_worker_id">Case Worker ID</label>
                                <input type="text" id="case_worker_id" name="case_worker_id" value="{{ old('case_worker_id', $survivor->case_worker_id ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div style="flex: 2;">
                        <label for="comments">Comments/Notes:</label>
                        <textarea id="comments" name="comments" rows="3">{{ old('comments', $survivor->comments ?? '') }}</textarea>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 12px;">
                    <div class="form-group" style="min-width:160px;">
                        <label style="color: #888; font-size: 14px;">Authored by:</label>
                        <div style="color: #888; font-size: 14px;">{{ $survivor->authored_by ?? '' }}</div>
                    </div>
                </div>
                <div class="form-row" style="margin-top: 12px;">
                    <div class="form-group" style="min-width:160px;">
                        <label style="color: #888; font-size: 14px;">Created:</label>
                        <div style="color: #888; font-size: 14px;">{{ $survivor->created_at ?? '' }}</div>
                    </div>
                    <div class="form-group" style="min-width:160px;">
                        <label style="color: #888; font-size: 14px;">Last Edited:</label>
                        <div style="color: #888; font-size: 14px;">{{ $survivor->updated_at ?? '' }}</div>
                    </div>

                    <button type="button" class="btn btn-secondary" onclick="window.history.back();" style="margin-right: 16px;">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ isset($survivor) ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

<script>
function toggleTTURow() {
    const ttuRow = document.getElementById('ttu-row');
    const hotelRow = document.getElementById('hotel-row');
    const stateparkRow = document.getElementById('statepark-row');
    const ttuRadio = document.querySelector('input[name="location_type"][value="TTU"]');
    const hotelRadio = document.querySelector('input[name="location_type"][value="Hotel"]');
    const stateparkRadio = document.querySelector('input[name="location_type"][value="State Park"]');
    if (ttuRadio && ttuRadio.checked) {
        ttuRow.style.display = '';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = 'none';
    } else if (hotelRadio && hotelRadio.checked) {
        ttuRow.style.display = 'none';
        hotelRow.style.display = '';
        stateparkRow.style.display = 'none';
    } else if (stateparkRadio && stateparkRadio.checked) {
        ttuRow.style.display = 'none';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = '';
    } else {
        ttuRow.style.display = 'none';
        hotelRow.style.display = 'none';
        stateparkRow.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleTTURow();
    document.querySelectorAll('input[name="location_type"]').forEach(function(radio) {
        radio.addEventListener('change', toggleTTURow);
    });
});
</script>