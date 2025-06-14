@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
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
                <!-- Main TTU Info -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="vin">VIN</label>
                        <input type="text" id="vin" name="vin" value="{{ old('vin', $ttu->vin ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer</label>
                        <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $ttu->manufacturer ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $ttu->brand ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" value="{{ old('model', $ttu->model ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" id="year" name="year" value="{{ old('year', $ttu->year ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="status">TTU Status</label>
                        <select id="status" name="status">
                            <option value="Not Ready for Occupancy (#b22222)" {{ old('status', $ttu->status ?? '') == 'Not Ready for Occupancy (#b22222)' ? 'selected' : '' }}>🔴 Not Ready for Occupancy</option>
                            <option value="Demobilized (#ffd700)" {{ old('status', $ttu->status ?? '') == 'Demobilized (#ffd700)' ? 'selected' : '' }}>🟡 Demobilized</option>
                            <option value="Ready for Transport (#228b22)" {{ old('status', $ttu->status ?? '') == 'Ready for Transport (#228b22)' ? 'selected' : '' }}>🟢 Ready for Transport</option>
                            <option value="Transferred to Auction (#007bff)" {{ old('status', $ttu->status ?? '') == 'Transferred to Auction (#007bff)' ? 'selected' : '' }}>🔵 Transferred to Auction</option>
                            <option value="Transferred to City/County/State Entity (#800080)" {{ old('status', $ttu->status ?? '') == 'Transferred to City/County/State Entity (#800080)' ? 'selected' : '' }}>🟣 Transferred to City/County/State Entity</option>
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
                    <div class="form-group left-label-field">
                        <label for="title-select" style="margin-right: 5px; font-size: 13px;">Do&nbsp;we&nbsp;have&nbsp;the&nbsp;Title?</label>
                        <select id="title-select" name="has_title">
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <!-- Location & Details -->
                <div class="form-row">
                    <div class="form-group" style="position: relative;">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $ttu->location ?? '') }}" autocomplete="off">
                        <div id="location-suggestions" class="autocomplete-suggestions" style="display:none; position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid #ccc; z-index:1000; max-height:200px; overflow-y:auto;"></div>
                    </div>
                    <div class="form-group">
                        <label for="unit_loc">Unit Loc./Lot #</label>
                        <input type="text" id="unit_loc" name="unit_loc" value="{{ old('unit_loc', $ttu->unit_loc ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" id="county" name="county" value="{{ old('county', $ttu->county ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="imei">IMEI# (GPS)</label>
                        <input type="text" id="imei" name="imei" value="{{ old('imei', $ttu->imei ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="text" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $ttu->purchase_price ?? '') }}">
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
                                $featureMap = [
                                    '200+ SQFT' => 'has_200sqft',
                                    'Prop. Fireplace' => 'has_propanefire',
                                    'TV' => 'has_tv',
                                    'Hydraulics' => 'has_hydraul',
                                    'Steps' => 'has_steps',
                                    'Teardrop Design' => 'has_teardrop',
                                    'Folding Walls' => 'has_foldwalls',
                                    'Outdoor Kitchen' => 'has_extkitchen',
                                ];
                            @endphp
                            @foreach($featureMap as $label => $field)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="{{ $field }}" value="1"
                                            {{ old($field, $ttu->$field ?? false) ? 'checked' : '' }}>
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
                                $statusMap = [
                                    'Onsite' => 'is_onsite',
                                    'Occupied' => 'is_occupied',
                                    'Winterized' => 'is_winterized',
                                    'Deblocked' => 'is_deblocked',
                                    'Cleaned' => 'is_cleaned',
                                    'GPS Removed' => 'is_gps_removed',
                                    'Being Donated' => 'is_being_donated',
                                    'Sold at Auction' => 'is_sold_at_auction',
                                ];
                            @endphp
                            @foreach($statusMap as $label => $field)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" name="{{ $field }}" value="1"
                                            {{ old($field, $ttu->$field ?? false) ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="form-column">
                        <div class="form-row">
                            <div class="form-group" style="flex:1;">
                                <label for="disposition">Disposition:</label>
                                <select id="disposition" name="disposition">
                                    <option value="Available" {{ old('disposition', $ttu->disposition ?? '') == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Awaiting Field team inspection" {{ old('disposition', $ttu->disposition ?? '') == 'Awaiting Field team inspection' ? 'selected' : '' }}>Awaiting Field team inspection</option>
                                    <option value="Awaiting Maintenance" {{ old('disposition', $ttu->disposition ?? '') == 'Awaiting Maintenance' ? 'selected' : '' }}>Awaiting Maintenance</option>
                                    <option value="Awaiting Pickup" {{ old('disposition', $ttu->disposition ?? '') == 'Awaiting Pickup' ? 'selected' : '' }}>Awaiting Pickup</option>
                                    <option value="Awaiting Signatures" {{ old('disposition', $ttu->disposition ?? '') == 'Awaiting Signatures' ? 'selected' : '' }}>Awaiting Signatures</option>
                                    <option value="Awaiting Transfer" {{ old('disposition', $ttu->disposition ?? '') == 'Awaiting Transfer' ? 'selected' : '' }}>Awaiting Transfer</option>
                                    <option value="Deployed" {{ old('disposition', $ttu->disposition ?? '') == 'Deployed' ? 'selected' : '' }}>Deployed</option>
                                    <option value="Keep on site for now" {{ old('disposition', $ttu->disposition ?? '') == 'Keep on site for now' ? 'selected' : '' }}>Keep on site for now</option>
                                    <option value="Occupied" {{ old('disposition', $ttu->disposition ?? '') == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="Officially Transferred" {{ old('disposition', $ttu->disposition ?? '') == 'Officially Transferred' ? 'selected' : '' }}>Officially Transferred</option>
                                    <option value="Pending Sale" {{ old('disposition', $ttu->disposition ?? '') == 'Pending Sale' ? 'selected' : '' }}>Pending Sale</option>
                                    <option value="Pending Transfer" {{ old('disposition', $ttu->disposition ?? '') == 'Pending Transfer' ? 'selected' : '' }}>Pending Transfer</option>
                                    <option value="Processing" {{ old('disposition', $ttu->disposition ?? '') == 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Ready for Backhaul" {{ old('disposition', $ttu->disposition ?? '') == 'Ready for Backhaul' ? 'selected' : '' }}>Ready for Backhaul</option>
                                    <option value="Sold" {{ old('disposition', $ttu->disposition ?? '') == 'Sold' ? 'selected' : '' }}>Sold</option>
                                    <option value="Staged" {{ old('disposition', $ttu->disposition ?? '') == 'Staged' ? 'selected' : '' }}>Staged</option>
                                    <option value="Storage" {{ old('disposition', $ttu->disposition ?? '') == 'Storage' ? 'selected' : '' }}>Storage</option>
                                    <option value="Unknown" {{ old('disposition', $ttu->disposition ?? '') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex:1; margin-right: 0;">
                                <label for="transpo_agency">Transport Agency:</label>
                                <input type="text" id="transpo_agency" name="transpo_agency" value="{{ old('transpo_agency', $ttu->transpo_agency ?? '') }}">
                            </div>
                        </div>
                        <!-- Donation Section -->
                        <div id="donation-section" class="form-section" style="margin-bottom: 10px; display: none;">
                            <div class="left-label-field">
                                <label>Is the recipient a State, City, County, or NPO?</label>
                                <select name="recipient_type" style="flex:0.2;">
                                    <option {{ old('recipient_type', $transfer->recipient_type ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                                    <option {{ old('recipient_type', $transfer->recipient_type ?? '') == 'NO' ? 'selected' : '' }}>NO</option>
                                </select>
                            </div>
                            <div class="left-label-field">
                                <label for="donation_agency" style="white-space:nowrap;">What Agency Is being given to?</label>
                                <input type="text" id="donation_agency" name="donation_agency" value="{{ old('donation_agency', $transfer->donation_agency ?? '') }}" style="max-width: 300px;">
                            </div>
                            <div class="left-label-field">
                                <label for="donation_category" style="white-space:nowrap;">Donation Category:</label>
                                <input type="text" id="donation_category" name="donation_category" value="{{ old('donation_category', $transfer->donation_category ?? '') }}" style="max-width: 300px;">
                            </div>
                        </div>

                        <!-- Sold At Auction Section -->
                        <div id="sold-at-auction-section" class="form-section" style="margin-bottom: 10px; display: none;">
                            <div class="left-label-field">
                                <label for="sold_at_auction_price">Sold At Auction Price</label>
                                <input type="text" id="sold_at_auction_price" name="sold_at_auction_price" value="{{ old('sold_at_auction_price', $transfer->sold_at_auction_price ?? '') }}" style="max-width: 150px;">
                            </div>
                            <div class="left-label-field">
                                <label for="recipient" style="white-space:nowrap;">Who is recipient?</label>
                                <input type="text" id="recipient" name="recipient" value="{{ old('recipient', $transfer->recipient ?? '') }}" style="max-width: 300px;">
                            </div>
                        </div>
                        <div class="form-section">    
                            <div class="remarks">
                                <div>
                                    <label for="remarks">Remarks:</label>
                                    <textarea id="remarks" name="remarks"></textarea>
                                </div>
                                <div>
                                    <label for="comments">Comments/Notes:</label>
                                    <textarea id="comments" name="comments"></textarea>
                                </div>
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
                                <label for="fema_id">FEMA-ID</label>
                                <input type="text" id="fema_id" name="fema_id" value="{{ old('fema_id', $selectedFemaId ?? '') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="survivor_name">Name</label>
                                <input type="text" id="survivor_name" name="survivor_name" value="{{ $survivor_name }}" readonly>
                            </div>
                            <input type="hidden" id="survivor_id" name="survivor_id" value="{{ old('survivor_id', $ttu->survivor_id ?? '') }}">
                            <div class="form-group">
                                <label for="lo">LO</label>
                                <select id="lo" name="lo">
                                    <option {{ old('lo', $ttu->lo ?? '') == 'NO' ? 'selected' : '' }}>NO</option>
                                    <option {{ old('lo', $ttu->lo ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lo-date">LO Date</label>
                                <input type="date" id="lo-date" name="lo_date" value="{{ old('lo_date', $ttu->lo_date ?? 'N/A') }}">
                            </div>
                            <div class="form-group">
                                <label for="est_lo_date">Est. LO Date</label>
                                <input type="date" id="est_lo_date" name="est_lo_date" value="{{ old('est_lo_date', $ttu->est_lo_date ?? 'N/A') }}">
                            </div>
                        </div>

                        <!-- PrivateSite Features -->
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;">
                            <h4 style="margin: 0;">Private Site</h4>
                            <label class="switch" style="margin: 0;">
                                <input type="checkbox" id="privatesite-switch" name="privatesite" value="1"
                                    {{ old('privatesite', $ttu->privatesite ?? false) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div id="privatesite-section" class="container form-section" style="display: {{ old('privatesite', $ttu->privatesite ?? false) ? 'flex' : 'none' }};">
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
                                  
                        <div class="form-footer">
                            <div class="info">
                                <div>
                                    <span>Authored by:</span>
                                    <span>{{ $authorName ?? '' }}</span>
                                </div>
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
<script src="{{ asset('js/ttusEdit.js') }}"></script>
@endsection
