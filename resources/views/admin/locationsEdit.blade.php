@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="EditLocation">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 0 auto;">
            <form method="POST" action="{{ ($type ?? '') === 'privatesite' && isset($privatesite) 
            ? route('admin.locations.update', $privatesite->id) 
            : (isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store')) }}">
                @csrf
                {{-- Hidden type so server knows which table to update even when the visible select is disabled on edit --}}
                <input type="hidden" name="type" value="{{ old('type', $type ?? '') }}">
                @if(isset($location) || isset($privatesite))
                    @method('PUT')
                @endif
                <div class="form-row" style="display: flex; gap: 24px; margin-bottom: 24px;">
                    <div class="form-group" style="flex: 1;">
                        <label for="type">Location Type</label>
                        <select name="type" id="type" required {{ (isset($location) || isset($privatesite)) ? 'disabled' : '' }}>
                            <option value="">Select Type</option>
                            <option value="hotel" {{ (old('type', $type ?? '') == 'hotel') ? 'selected' : '' }}>hotel</option>
                            <option value="statepark" {{ (old('type', $type ?? '') == 'statepark') ? 'selected' : '' }}>statepark</option>
                            <option value="privatesite" {{ (old('type', $type ?? '') == 'privatesite') ? 'selected' : '' }}>privatesite</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="name">Location Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $location->name ?? $privatesite->name ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" required value="{{ old('address', $location->address ?? $privatesite->address ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="phone">Phone #</label>
                        <input type="text" name="phone" id="phone" required value="{{ old('phone', $location->phone ?? $privatesite->phone ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="contact_name">Contact Name</label>
                        <input type="text" name="contact_name" id="contact_name" required value="{{ old('contact_name', $location->contact_name ?? $privatesite->contact_name ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                </div>
				<div class="form-row" style="display: flex; gap: 24px; margin-bottom: 24px; align-items:center;">
					<div class="form-group" style="flex: 0 1 200px;">
						<label for="fdec_id">FDEC</label>
						@php
							$selectedFdec = old('fdec_id', $location->fdec_id ?? $privatesite->fdec_id ?? []);
							if (!is_array($selectedFdec)) {
								$decoded = json_decode($selectedFdec, true);
								$selectedFdec = is_array($decoded) ? $decoded : [];
							}
						@endphp

						<select id="fdec_id" name="fdec_id[]" multiple>
							@foreach($fdecList ?? [] as $f)
								<option value="{{ $f->id }}" {{ in_array((string)$f->id, array_map('strval', $selectedFdec), true) ? 'selected' : '' }}>
									{{ $f->fdec_no ?? $f->name ?? $f->id }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="form-group" style="flex: 0 0 auto; display:flex; align-items:center; gap:8px;">
						<label for="pet_friendly" style="margin:0;">Pet-friendly</label>
						<label class="switch" style="margin:0;">
							<input type="checkbox" id="pet_friendly" name="pet_friendly" value="1"
								{{ old('pet_friendly', ($location->pet_friendly ?? $privatesite->pet_friendly ?? false)) ? 'checked' : '' }}
								{{ !empty($readonly) ? 'disabled' : '' }}>
							<span class="slider"></span>
						</label>
					</div>
				</div>
                <div id="privatesite-section" class="form-row"  style="align-items: unset">
                    <div class="column">
                        <table>
                            @php
                                $siteFeatureMap = [
                                    'Power' => 'pow',
                                    'Water' => 'h2o',
                                    'Sewage' => 'sew',
                                    'Own Property' => 'own',
                                    'Residential' => 'res',
                                ];
                            @endphp
                            @foreach($siteFeatureMap as $label => $field)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="{{ $field }}" value="1"
                                            {{ old($field, $privatesite->$field ?? false) ? 'checked' : '' }}
                                            {{ !empty($readonly) ? 'disabled' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="form-column">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="damage_assessment">Damage Assessment:</label>
                                <textarea id="damage_assessment" name="damage_assessment" {{ !empty($readonly) ? 'readonly' : '' }}>{{ old('damage_assessment', $privatesite->damage_assessment ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ehp">EHP:</label>
                                <input type="text" id="ehp" name="ehp" value="{{ old('ehp', $privatesite->ehp ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="ehp_notes">EHP Notes:</label>
                                <textarea id="ehp_notes" name="ehp_notes" {{ !empty($readonly) ? 'readonly' : '' }}>{{ old('ehp_notes', $privatesite->ehp_notes ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dow_long">DOW Longitude:</label>
                                <input type="text" id="dow_long" name="dow_long" value="{{ old('dow_long', $privatesite->dow_long ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="dow_lat">DOW Latitude:</label>
                                <input type="text" id="dow_lat" name="dow_lat" value="{{ old('dow_lat', $privatesite->dow_lat ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="zon">Zoning:</label>
                                <input type="text" id="zon" name="zon" value="{{ old('zon', $privatesite->zon ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dow_response">DOW Response:</label>
                                <textarea id="dow_response" name="dow_response" {{ !empty($readonly) ? 'readonly' : '' }}>{{ old('dow_response', $privatesite->dow_response ?? '') }}</textarea>
                            </div>
                        </div>
                        @if($type === 'privatesite' && isset($privatesite))
                            <div class="form-section" style="margin-bottom: 24px;">
                                <h4>Assigned TTU</h4>
                                @if(isset($ttu))
                                    <div class="form-row" style="align-items: flex-end">
                                        <div class="form-group">
                                            <label for="vin">VIN:</label>
                                            <input type="text" id="vin" name="vin" value="{{ $ttu->vin ?? '-' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            @php
                                                // Example: $status = "Occupied (#ffc107)"
                                                $statusRaw = $ttu->status ?? '-';
                                                $status = $statusRaw;
                                                $color = '#888';

                                                // Extract color code from status string if present in parentheses
                                                if (preg_match('/\((#[0-9a-fA-F]{6})\)/', $statusRaw, $matches)) {
                                                    $color = $matches[1];
                                                    // Remove the color code from the status string for display
                                                    $status = trim(str_replace($matches[0], '', $statusRaw));
                                                }
                                            @endphp
                                            <span style="display:inline-block; width:14px; height:14px; border-radius:50%; background:{{ $color }}; margin-right:8px; vertical-align:middle;"></span>
                                            <input type="text" id="status" name="status" value="{{ $status }}" readonly style="width:auto; display:inline-block;">
                                        </div>                                           
                                        <button class="btn btn-primary" type="button" style="margin-bottom: 10px"
                                            onclick="window.location.href='{{ route('admin.ttus.view', $ttu->id) }}'">
                                            Go-to Record
                                        </button>
                                    </div>
                                @else
                                    <div>No TTU assigned to this Private Site.</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-footer">
                    <div></div>
                    <div class="buttons">
                        @if(!empty($readonly))
                            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.locations') }}';" style="margin-right: 16px;">Back</button>
                            <a href="" class="btn btn-save">Show History</a>
                        @else
                            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.locations') }}';" style="margin-right: 16px;">Cancel</button>
                            <button type="submit" class="btn btn-save">{{ isset($location) ? 'Update' : 'Save' }}</button>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Show rooms or lodge_units --}}
            @if(isset($location) && (($type === 'hotel' && isset($rooms)) || ($type === 'statepark' && isset($lodge_units))))
                @php
                    $ishotel = $type === 'hotel';
                    $items = $ishotel ? $rooms : $lodge_units;
                    $numberLabel = $ishotel ? 'Room #' : 'Unit #';
                    $addBtnId = $ishotel ? 'addRoomButton' : 'addUnitButton';
                    $title = $ishotel ? 'Rooms' : 'Lodge Units';
                @endphp
                <div class="form-section" style="margin: 24px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4>{{ $title }}</h4>
                        @if(empty($readonly))
                            <a class="add-new-button" id="{{ $addBtnId }}">Add New</a>
                        @endif
                    </div>
                    <table>
                        <thead>
                            <tr>
                                @if(!$ishotel)
                                    <th>Unit Type</th>
                                @endif
                                <th>{{ $numberLabel }}</th>
                                <th>Survivor</th>
                                <th>HH Size</th>
                                @if(empty($readonly))
                                    <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    @if(!$ishotel)
                                        <td>{{ $item->unit_type ?? '-' }}</td>
                                    @endif
                                    <td>{{ $ishotel ? $item->room_num : $item->unit_name }}</td>
                                    <td>
                                        @if(!empty($item->fname) && !empty($item->lname) && isset($item->id))
                                            <a href="{{ route('admin.survivors.view', $item->id) }}">
                                                {{ $item->survivor_name }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->hh_size ?? '-' }}</td>
                                    @if(empty($readonly))
                                    <td class="text-right">                                        
                                            <button type="button"
                                                class="btn btn-sm btn-primary edit-unit-btn"
                                                data-id="{{ $item->room_id ?? $item->lodge_unit_id ?? '' }}"
                                                data-type="{{ $ishotel ? 'hotel' : 'statepark' }}"
                                                data-unit="{{ $ishotel ? $item->room_num : $item->unit_name }}"
                                                data-daily-rate="{{ $item->daily_rate ?? '' }}"
                                                @if(!$ishotel)
                                                    data-unit-type="{{ $item->unit_type ?? '' }}"
                                                @endif
                                            >
                                                Edit
                                            </button>
                                            <form method="POST"
                                                action="{{ $ishotel
                                                    ? route('admin.rooms.delete', $item->room_id)
                                                    : route('admin.lodge_units.delete', $item->lodge_unit_id) }}"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to remove this {{ $ishotel ? 'room' : 'unit' }}?');">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif            


            <div>
                <div class="info">
                    <div>
                        <span>Authored by:</span>
                        <span>{{ $location->author ?? '' }}</span>
                    </div>
                    @if(isset($location))
                        <div style="display: flex; gap: 40px;">
                            <span>Created: {{ $location->created_at }}</span>
                            <span>Last Edited: {{ $location->updated_at }}</span>
                        </div>
                    @endif
                </div>
            </div>


            {{-- Room/Unit Modal OUTSIDE the main form --}}
            @if(isset($location))
            <div id="roomUnitModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h4 id="modalTitle">Add New Room</h4>
                    <form id="roomUnitForm" method="POST" action="{{ $type === 'hotel' ? route('admin.rooms.store') : route('admin.lodge_units.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="modalLocationName">{{ $type === 'hotel' ? 'Hotel' : 'State Park' }} Name</label>
                            <input type="text" id="modalLocationName" class="form-control" value="{{ $location->name ?? '' }}" readonly>
                        </div>
                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                        @if($type === 'statepark')
                        <div class="form-group">
                            <label for="modalUnitType">Unit Type</label>
                            <select name="unit_type" id="modalUnitType" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="lot">Lot</option>
                                <option value="cabin">Cabin</option>
                                <option value="lodge">Lodge</option>
                            </select>
                        </div>
                        @endif
                        <div class="form-row" style="display:flex; gap:16px;">
                            <div class="form-group" style="flex:1;">
                                <label id="modalLabelNumber" for="number">{{ $type === 'hotel' ? 'Room #' : 'Unit #' }}</label>
                                <input type="text" name="number" id="modalNumber" class="form-control" required>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label for="modalDailyRate">Daily Rate</label>
								<div style="position:relative;">
									<span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#777;">$</span>
									<input type="text" name="daily_rate" id="modalDailyRate" class="form-control" style="padding-left:22px;">
								</div>
                            </div>
                        </div>
                        
                        <div style="margin-top:16px;">
                            <button type="button" class="btn btn-cancel" id="cancelModalBtn" style="margin-right:12px;">Cancel</button>
                            <button type="submit" class="btn btn-save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<script>
    window.locationType = @json($type ?? '');
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/locationsEdit.js') }}"></script>
@endsection
