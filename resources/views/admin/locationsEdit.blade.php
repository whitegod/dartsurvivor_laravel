@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="EditLocation">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 0 auto;">
            <form method="POST" action="{{ ($type ?? '') === 'Private Site' && isset($privatesite) 
            ? route('admin.locations.update', $privatesite->id) 
            : (isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store')) }}">
                @csrf
                @if(($type ?? '') === 'Private Site' && isset($privatesite))
                    @method('PUT')
                @elseif(isset($location))
                    @method('PUT')
                @endif
                {{-- Only show the top row for Hotel or State Park --}}
                @if(($type ?? '') !== 'Private Site')
                    <div class="form-row" style="display: flex; gap: 24px; margin-bottom: 24px;">
                        @if(isset($location))
                            <input type="hidden" name="type" value="{{ $type }}">
                        @else
                            <div class="form-group" style="flex: 1;">
                                <label for="type">Location Type</label>
                                <select name="type" id="type" required>
                                    <option value="">Select Type</option>
                                    <option value="Hotel" {{ (old('type', $type ?? '') == 'Hotel') ? 'selected' : '' }}>Hotel</option>
                                    <option value="State Park" {{ (old('type', $type ?? '') == 'State Park') ? 'selected' : '' }}>State Park</option>
                                    <option value="Private Site" {{ (old('type', $type ?? '') == 'Private Site') ? 'selected' : '' }}>Private Site</option>
                                </select>
                            </div>
                        @endif
                        <div class="form-group" style="flex: 1;">
                            <label for="name">Location Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $location->name ?? '') }}">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" required value="{{ old('address', $location->address ?? '') }}">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="phone">Phone #</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $location->phone ?? '') }}">
                        </div>
                    </div>
                @elseif(($type ?? '') === 'Private Site')
                    <input type="hidden" name="type" value="Private Site">
                @endif

                {{-- Show rooms or lodge_units --}}
                @if(isset($location) && (($type === 'Hotel' && isset($rooms)) || ($type === 'State Park' && isset($lodge_units))))
                    @php
                        $isHotel = $type === 'Hotel';
                        $items = $isHotel ? $rooms : $lodge_units;
                        $numberLabel = $isHotel ? 'Room #' : 'Unit #';
                        $addBtnId = $isHotel ? 'addRoomButton' : 'addUnitButton';
                        $title = $isHotel ? 'Rooms' : 'Lodge Units';
                    @endphp
                    <div class="form-section" style="margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4>{{ $title }}</h4>
                            <a class="add-new-button" id="{{ $addBtnId }}">Add New</a>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ $numberLabel }}</th>
                                    <th>Survivor</th>
                                    <th>HH Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $isHotel ? $item->room_num : $item->unit_name }}</td>
                                        <td>{{ $item->survivor_name ?? '-' }}</td>
                                        <td>{{ $item->hh_size ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Only show privatesite-section if type is Private Site --}}
                @if(($type ?? '') === 'Private Site')
                    <input type="hidden" name="type" value="Private Site">
                    <div id="privatesite-section" class="form-row" style="align-items: unset">
                        <div class="column">
                            <div>
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $privatesite->name ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" name="address" value="{{ old('address', $privatesite->address ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $privatesite->phone ?? '') }}">
                                </div>
                            </div>
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
                                                {{ old($field, $privatesite->$field ?? false) ? 'checked' : '' }}>
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
                                    <textarea id="damage_assessment" name="damage_assessment">{{ old('damage_assessment', $privatesite->damage_assessment ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="ehp">EHP:</label>
                                    <input type="text" id="ehp" name="ehp" value="{{ old('ehp', $privatesite->ehp ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="ehp_notes">EHP Notes:</label>
                                    <textarea id="ehp_notes" name="ehp_notes">{{ old('ehp_notes', $privatesite->ehp_notes ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dow_long">DOW Longitude:</label>
                                    <input type="text" id="dow_long" name="dow_long" value="{{ old('dow_long', $privatesite->dow_long ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="dow_lat">DOW Latitude:</label>
                                    <input type="text" id="dow_lat" name="dow_lat" value="{{ old('dow_lat', $privatesite->dow_lat ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label for="zon">Zoning:</label>
                                    <input type="text" id="zon" name="zon" value="{{ old('zon', $privatesite->zon ?? '') }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="dow_response">DOW Response:</label>
                                    <textarea id="dow_response" name="dow_response">{{ old('dow_response', $privatesite->dow_response ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-footer">
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
                    <div class="buttons">
                        <button type="button" class="btn btn-cancel" onclick="window.history.back();" style="margin-right: 16px;">Cancel</button>
                        <button type="submit" class="btn btn-save">{{ isset($location) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
            {{-- Room/Unit Modal OUTSIDE the main form --}}
            @if(isset($location))
            <div id="roomUnitModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h4 id="modalTitle">Add New Room</h4>
                    <form id="roomUnitForm" method="POST" action="{{ $type === 'Hotel' ? route('admin.rooms.store') : route('admin.lodge_units.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="modalLocationName">{{ $type === 'Hotel' ? 'Hotel' : 'State Park' }} Name</label>
                            <input type="text" id="modalLocationName" class="form-control" value="{{ $location->name ?? '' }}" readonly>
                        </div>
                        <input type="hidden" name="location_id" value="{{ $location->id }}">
                        <div class="form-group">
                            <label id="modalLabelNumber" for="number">{{ $type === 'Hotel' ? 'Room #' : 'Unit #' }}</label>
                            <input type="text" name="number" id="modalNumber" class="form-control" required>
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
</script>
<script src="{{ asset('js/locationsEdit.js') }}"></script>
@endsection