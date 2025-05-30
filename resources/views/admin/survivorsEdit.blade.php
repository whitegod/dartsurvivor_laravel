@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
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
                        <label for="fema_id">FEMA-ID</label>
                        <input type="text" id="fema_id" name="fema_id" value="{{ old('fema_id', $survivor->fema_id ?? '') }}">
                    </div>
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
                                <input type="text" name="vin" id="vin-autocomplete" value="{{ old('vin', $ttu->vin ?? '') }}" style="width: 260px;" autocomplete="off">
                                <button class="btn btn-primary" type="button">
                                    Go-to Record
                                </button>
                            </div>
                            <div id="vin-suggestions" style="position:relative; z-index:10;"></div>
                        </div>
                        <div class="form-group" style="flex:0.5; min-width:70px;">
                            <label for="lo">LO</label>
                            <select id="lo" name="lo">
                                <option value="0" {{ old('lo', isset($ttu) ? $ttu->lo : '') == '0' ? 'selected' : '' }}>NO</option>
                                <option value="1" {{ old('lo', isset($ttu) ? $ttu->lo : '') == '1' ? 'selected' : '' }}>YES</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lo_date">LO Date</label>
                            <input id="lo_date" name="lo_date" type="date" value="{{ old('lo_date', $ttu->lo_date ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="est_lo_date">Est. LO Date</label>
                            <input id="est_lo_date" name="est_lo_date" type="date" value="{{ old('est_lo_date', $ttu->est_lo_date ?? '') }}">
                        </div>
                    </div>
                </div>
                <div id="hotel-row" style="{{ $locationType == 'Hotel' ? '' : 'display:none;' }}; margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned Hotel</label>
                    <div class="form-row">
                        <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                            <label class="info" style="margin-bottom:6px;">Hotel Name</label>
                            <div style="display:flex; gap:4px;">
                                <input type="text" name="hotel_name"
                                    value="{{ old('hotel_name', $hotelName ?? $survivor->hotel_name ?? '') }}"
                                    style="width: 200px;">

                                <select name="hotel_room" id="hotel_room_select" style="min-width:160px;">
                                    @if(old('hotel_room', $hotelRoom ?? false))
                                        <option value="{{ old('hotel_room', $hotelRoom ?? '') }}" selected>
                                            {{ old('hotel_room', $hotelRoom ?? '') }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div id="hotel-suggestions" style="position:absolute; z-index:1000; background-color: #fff; border:1px solid #ddd;"></div>
                        </div>
                        <div class="form-group">
                            <label for="hotel_li_date">LI Date</label>
                            <input id="hotel_li_date" name="hotel_li_date" type="date"
                                value="{{ old('hotel_li_date', $hotelLiDate ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="hotel_lo_date">LO Date</label>
                            <input id="hotel_lo_date" name="hotel_lo_date" type="date"
                                value="{{ old('hotel_lo_date', $hotelLoDate ?? '') }}">
                        </div>
                    </div>
                </div>
                <div id="statepark-row" style="{{ $locationType == 'State Park' ? '' : 'display:none;' }}; margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned State Park</label>
                    <div class="form-row">
                        <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                            <label class="info" style="margin-bottom:6px;">State Park Name</label>
                            <div style="position:relative; display:flex; gap:4px;">
                                <input type="text" name="statepark_name" value="{{ old('statepark_name', $stateparkName ?? $survivor->statepark_name ?? '') }}">
                                <div id="statepark-suggestions" style="position:absolute; top:100%; left:0; z-index:1000; background-color: #fff; border:1px solid #ddd;"></div>
                                <select name="unit_name" id="unit_name_select">
                                    <!-- JS will populate -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="statepark_li_date">LI Date</label>
                            <input id="statepark_li_date" name="statepark_li_date" type="date" value="{{ old('statepark_li_date', $stateparkLiDate ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="statepark_lo_date">LO Date</label>
                            <input id="statepark_lo_date" name="statepark_lo_date" type="date" value="{{ old('statepark_lo_date', $stateparkLoDate ?? '') }}">
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
                                <label for="caseworker_id">Case Worker ID</label>
                                <input type="text" id="caseworker_id" name="caseworker_id" value="{{ old('caseworker_id', $survivor->caseworker_id ?? '') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div style="flex: 2;">
                        <label for="notes">Comments/Notes:</label>
                        <textarea id="notes" name="notes" rows="3">{{ old('notes', $survivor->notes ?? '') }}</textarea>
                    </div>
                </div>
                <div class="form-footer">
                    <div class="info">
                        <div>
                            <span>Authored by:</span>
                            <span>{{ $survivor->author ?? '' }}</span>
                        </div>
                        @if(isset($survivor))
                            <div style="display: flex; gap: 40px;">
                                <span>Created: {{ $survivor->created_at }}</span>
                                <span>Last Edited: {{ $survivor->updated_at }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="buttons">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back();" style="margin-right: 16px;">Cancel</button>
                        <button type="submit" class="btn btn-save">{{ isset($survivor) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    window.initialSelectedUnit = @json(old('unit_name', $unitName ?? $survivor->unit_name ?? ''));
    window.initialSelectedRoom = @json(old('hotel_room', $hotelRoom ?? ''));
    window.initialHotelName = @json(old('hotel_name', $hotelName ?? $survivor->hotel_name ?? ''));
    window.initialStateparkName = @json(old('statepark_name', $stateparkName ?? $survivor->statepark_name ?? ''));
</script>
<script src="{{ asset('js/survivorsEdit.js') }}"></script>
@endsection
