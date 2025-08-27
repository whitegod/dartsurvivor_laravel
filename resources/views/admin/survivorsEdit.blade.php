@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
@endsection

@php
    $locationTypeRaw = old('location_type', $survivor->location_type ?? 'TTU');
    $locationType = is_array($locationTypeRaw) ? $locationTypeRaw : json_decode($locationTypeRaw, true);
    $checkedLocationType = $locationType ?? [];
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
                        <input type="text" id="fname" name="fname" value="{{ old('fname', $survivor->fname ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }} required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" value="{{ old('lname', $survivor->lname ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }} required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ $survivor->address ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex:4;">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ $survivor->city ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex:1.2;">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="{{ $survivor->state ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex:1.2;">
                        <label for="zip">Zip</label>
                        <input type="text" id="zip" name="zip" value="{{ $survivor->zip ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fema_id">FEMA-ID</label>
                        <input type="text" id="fema_id" name="fema_id" value="{{ old('fema_id', $survivor->fema_id ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="primary_phone">Primary Phone</label>
                        <input type="text" id="primary_phone" name="primary_phone" value="{{ $survivor->primary_phone ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="secondary_phone">Secondary Phone</label>
                        <input type="text" id="secondary_phone" name="secondary_phone" value="{{ $survivor->secondary_phone ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" id="county" name="county" value="{{ $survivor->county ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="own_rent">Own/Rent</label>
                        <select id="own_rent" name="own_rent" {{ !empty($readonly) ? 'disabled' : '' }}>
                            <option value="Own" {{ (old('own_rent', $survivor->own_rent ?? '') == 'Own') ? 'selected' : '' }}>Own</option>
                            <option value="Rent" {{ (old('own_rent', $survivor->own_rent ?? '') == 'Rent') ? 'selected' : '' }}>Rent</option>
                        </select>
                    </div>
                </div>

                <label style="font-size:16px; margin-bottom:2px;">Family Information</label>
                <div class="form-row">
                    <div class="form-group">
                        <label for="group0_2">0-2 years old</label>
                        <input type="text" id="group0_2" name="group0_2" value="{{ old('group0_2', $survivor->group0_2 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group3_6">3-6 years old</label>
                        <input type="text" id="group3_6" name="group3_6" value="{{ old('group3_6', $survivor->group3_6 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group7_12">7-12 years old</label>
                        <input type="text" id="group7_12" name="group7_12" value="{{ old('group7_12', $survivor->group7_12 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group13_17">13-17 years old</label>
                        <input type="text" id="group13_17" name="group13_17" value="{{ old('group13_17', $survivor->group13_17 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group18_21">18-21 years old</label>
                        <input type="text" id="group18_21" name="group18_21" value="{{ old('group18_21', $survivor->group18_21 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group22_65">22-65 years old</label>
                        <input type="text" id="group22_65" name="group22_65" value="{{ old('group22_65', $survivor->group22_65 ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="group65plus">65+ years old</label>
                        <input type="text" id="group65plus" name="group65plus" value="{{ old('group65plus', $survivor->group65plus ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="hh_size">Household Size</label>
                        <input type="text" id="hh_size" name="hh_size" value="{{ $survivor->hh_size ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="pets">Pets</label>
                        <input type="text" id="pets" name="pets" value="{{ $survivor->pets ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="form-row" style="align-items: end">
                    <div class="form-group" style="flex:1;">
                        <label for="li_date">LI Date</label>
                        <input type="date" id="li_date" name="li_date" value="{{ $survivor->li_date ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $survivor->email ?? '' }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="min-width:220px;">
                        <div style="display: flex; gap: 24px; align-items: center;">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                TTU
                                @php
                                    $checkedLocationType = old('location_type', $locationType ?? []);
                                @endphp

                                <input type="checkbox" name="location_type[]" value="TTU"
                                    {{ is_array($checkedLocationType) && in_array('TTU', $checkedLocationType) ? 'checked' : '' }}
                                    class="custom-checkbox-square" {{ !empty($readonly) ? 'disabled' : '' }}>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                Hotel
                                <input type="checkbox" name="location_type[]" value="Hotel"
                                    {{ is_array(old('location_type', $locationType ?? [])) && in_array('Hotel', old('location_type', $locationType ?? [])) ? 'checked' : '' }}
                                    class="custom-checkbox-square" {{ !empty($readonly) ? 'disabled' : '' }}>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                State Park
                                <input type="checkbox" name="location_type[]" value="State Park"
                                    {{ is_array(old('location_type', $locationType ?? [])) && in_array('State Park', old('location_type', $locationType ?? [])) ? 'checked' : '' }}
                                    class="custom-checkbox-square" {{ !empty($readonly) ? 'disabled' : '' }}>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="ttu-row" class="{{ in_array('TTU', $locationType ?? []) ? '' : 'hidden' }}" style="margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned TTU</label>
                    <div id="ttu-form-rows">
                        @php
                            $ttus = $ttus ?? [null]; // If empty, show one blank row
                        @endphp
                        @foreach($ttus as $ttu)
                        <div class="form-row">
                            <div class="form-group" style="flex:4; min-width:220px; margin-right: 100px;">
                                <label style="margin-bottom:6px;">VIN</label>
                                <div style="display:flex; gap:4px;">
                                    <input type="text" name="vin[]" class="vin-autocomplete" value="{{ old('vin.' . $loop->index, $ttu->vin ?? '') }}" style="width: 260px;" autocomplete="off" {{ !empty($readonly) ? 'readonly' : '' }}>
                                    <button class="btn btn-primary" type="button"
                                        @if(!empty($ttu))
                                            onclick="window.location.href='{{ route('admin.ttus.view', $ttu->id) }}'"
                                        @else
                                            disabled
                                        @endif
                                    >
                                        Go-to Record
                                    </button>
                                </div>
                                <div class="vin-suggestions" style="position:relative; z-index:10;"></div>
                            </div>
                            <div class="form-group">
                                <label>LI Date</label>
                                <input name="ttu_li_date[]" type="date" value="{{ old('ttu_li_date.' . $loop->index, $ttu->li_date ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>LO Date</label>
                                <input name="lo_date[]" type="date" value="{{ old('lo_date.' . $loop->index, $ttu->lo_date ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>Est. LO Date</label>
                                <input name="est_lo_date[]" type="date" value="{{ old('est_lo_date.' . $loop->index, $ttu->est_lo_date ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(empty($readonly))
                        <button type="button" class="btn btn-secondary" id="add-ttu-btn" style="margin-top:10px;">Add More</button>
                    @endif
                </div>
                <div id="hotel-row" class="{{ (is_array(old('location_type', $locationType ?? [])) && in_array('Hotel', old('location_type', $locationType ?? []))) ? '' : 'hidden' }}" style="margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned Hotel</label>
                    <div id="hotel-form-rows">
                        @php
                            $hotelRooms = $hotelRooms ?? [null]; // $hotelRooms should be an array of Room models or nulls
                        @endphp
                        @foreach($hotelRooms as $i => $room)
                        <div class="form-row hotel-row">
                            <div class="form-group">
                                <label>Hotel Name</label>
                                <input type="text" name="hotel_name[]" value="{{ old('hotel_name.' . $i, $room->hotel->name ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                                <div class="hotel-suggestions" style="position:relative; z-index:10;"></div>
                            </div>
                            <div class="form-group">
                                <label>Room #</label>
                                <input type="text" name="hotel_room[]" value="{{ old('hotel_room.' . $i, $room->room_num ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                                <div class="room-suggestions" style="position:relative; z-index:10;"></div>
                            </div>
                            <div class="form-group">
                                <label>LI Date</label>
                                <input type="date" name="hotel_li_date[]" value="{{ old('hotel_li_date.' . $i, $room->li_date ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>LO Date</label>
                                <input type="date" name="hotel_lo_date[]" value="{{ old('hotel_lo_date.' . $i, $room->lo_date ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(empty($readonly))
                        <button type="button" class="btn btn-secondary" id="add-hotel-btn">Add More</button>
                    @endif
                </div>
                <div id="statepark-row" class="{{ (is_array(old('location_type', $locationType ?? [])) && in_array('State Park', old('locationType', $locationType ?? []))) ? '' : 'hidden' }}" style="margin-bottom: 24px; border:1px solid #ddd; border-radius:8px; padding:18px 18px 10px 18px;">
                    <label style="font-weight:bold; font-size:16px; margin-bottom:2px;">Assigned State Park</label>
                    @php
                    $stateparkUnits = $stateparkUnits ?? [null]; // Ensure at least one row
                    @endphp
                    @foreach($stateparkUnits as $i => $unit)
                    <div class="form-row statepark-row">
                        <div class="form-group" style="flex:4;">
                            <label style="margin-bottom:6px;">State Park Name</label>
                            <div style="display:flex; gap:4px;">
                                <div style="position:relative;">
                                    <input type="text" name="statepark_name[]" style="position:relative;"
                                        value="{{ old('statepark_name.' . $i, $unit->statepark_name ?? '') }}" 
                                        {{ !empty($readonly) ? 'readonly' : '' }}>
                                    <div class="statepark-suggestions" style="position:absolute; left:0; top:100%; width:100%; z-index:1000;"></div>
                                </div>    
                                <div style="position:relative;">
                                    <input type="text" name="unit_name[]" style="position:relative;" class="unit-name-autocomplete"
                                        value="{{ old('unit_name.' . $i, $unit->unit_name ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                                    <div class="unit-suggestions" style="position:absolute; left:0; top:100%; width:100%; z-index:1000;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>LI Date</label>
                            <input name="statepark_li_date[]" type="date" 
                                value="{{ old('statepark_li_date.' . $i, $unit->li_date ?? '') }}" 
                                {{ !empty($readonly) ? 'readonly' : '' }}>
                        </div>
                        <div class="form-group">
                            <label>LO Date</label>
                            <input name="statepark_lo_date[]" type="date" 
                                value="{{ old('statepark_lo_date.' . $i, $unit->lo_date ?? '') }}" 
                                {{ !empty($readonly) ? 'readonly' : '' }}>
                        </div>
                    </div>
                    @endforeach
                    @if(empty($readonly))
                        <button type="button" class="btn btn-secondary" id="add-statepark-btn" style="margin-top:10px;">Add More</button>
                    @endif
                </div>

                <div class="form-row" style="display: flex; align-items: start; gap: 32px;">
                    <!-- Left Column -->
                    <div style="flex: 1; min-width: 260px; max-width: 340px;">
                        <div class="form-group" style="min-width:120px; max-width:180px;">
                            <label for="opt_out">Opt Out?</label>
                            <select id="opt_out" name="opt_out" {{ !empty($readonly) ? 'disabled' : '' }}>
                                <option value="NO" {{ (old('opt_out', $survivor->opt_out ?? '') == 'NO') ? 'selected' : '' }}>NO</option>
                                <option value="YES" {{ (old('opt_out', $survivor->opt_out ?? '') == 'YES') ? 'selected' : '' }}>YES</option>
                            </select>
                        </div>
                        <div style="display: flex; gap: 16px;">
                            <div class="form-group" style="min-width:120px; max-width:180px;">
                                <label for="opt_out_reason">Reason</label>
                                <select id="opt_out_reason" name="opt_out_reason" {{ !empty($readonly) ? 'disabled' : '' }}>
                                    <option value="N/A" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'N/A') ? 'selected' : '' }}>N/A</option>
                                    <option value="Personal" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'Personal') ? 'selected' : '' }}>Personal</option>
                                    <option value="Other" {{ (old('opt_out_reason', $survivor->opt_out_reason ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group" style="min-width:120px; max-width:180px;">
                                <label for="caseworker_id">Case Worker ID</label>
                                <input type="text" id="caseworker_id" name="caseworker_id" value="{{ old('caseworker_id', $survivor->caseworker_id ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                        </div>
                    </div>
                    <!-- Right Column -->
                    <div style="flex: 2;">
                        <div>
                            <label for="notes">Comments/Notes:</label>
                            <textarea id="notes" name="notes" rows="3" {{ !empty($readonly) ? 'readonly' : '' }}>{{ old('notes', $survivor->notes ?? '') }}</textarea>
                        </div>
                        <div class="info" style="margin-top: 35px;">
                            <span>Total Dates:</span>
                            <span>
                                @php
                                    // Determine which LI/LO fields to use based on location type
                                    $locationType = old('location_type', $survivor->location_type ?? 'TTU');
                                    if ($locationType === 'TTU') {
                                        $li = old('li_date', $ttu->li_date ?? null);
                                        $lo = old('lo_date', $ttu->lo_date ?? null);
                                        $loFlag = old('lo', $ttu->lo ?? null);
                                    } elseif ($locationType === 'Hotel') {
                                        $li = old('hotel_li_date', $hotelLiDate ?? null);
                                        $lo = old('hotel_lo_date', $hotelLoDate ?? null);
                                        $loFlag = null; // Not used for hotel
                                    } elseif ($locationType === 'State Park') {
                                        $li = old('statepark_li_date', $stateparkLiDate ?? null);
                                        $lo = old('statepark_lo_date', $stateparkLoDate ?? null);
                                        $loFlag = null; // Not used for state park
                                    } else {
                                        $li = null;
                                        $lo = null;
                                        $loFlag = null;
                                    }

                                    $liDate = $li ? \Carbon\Carbon::parse($li) : null;
                                    // If LO date is not set or LO is "NO" (0), use today as end date
                                    $endDate = (!$lo || (isset($loFlag) && ($loFlag === '0' || $loFlag === 'NO'))) ? \Carbon\Carbon::today() : \Carbon\Carbon::parse($lo);
                                    $totalDates = ($liDate) ? $liDate->diffInDays($endDate) + 1 : '';
                                @endphp
                                {{ $totalDates !== '' ? $totalDates : 'N/A' }}
                            </span>
                        </div>
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
                        @if(!empty($readonly))
                            <button type="button" class="btn btn-cancel" onclick="window.history.back();" style="margin-right: 16px;">Back</button>
                            <a href="" class="btn btn-save">Show History</a>
                        @else
                            <button type="button" class="btn btn-cancel" onclick="window.history.back();" style="margin-right: 16px;">Cancel</button>
                            <button type="submit" class="btn btn-save">{{ isset($survivor) ? 'Update' : 'Save' }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    window.initialSelectedUnit = @json(old('unit_name', $survivor->unit_name ?? ''));
    window.initialSelectedRoom = @json(old('hotel_room', $hotelRoom ?? ''));
    window.initialHotelName = @json(old('hotel_name', $hotelName ?? $survivor->hotel_name ?? ''));
    window.initialStateparkName = @json(old('statepark_name', $stateparkName ?? $survivor->statepark_name ?? ''));
</script>
<script src="{{ asset('js/survivorsEdit.js') }}"></script>
@endsection
