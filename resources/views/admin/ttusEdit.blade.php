@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
@endsection

@section('content')
<section id="TTUEdit">
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
                        <input type="text" id="vin" name="vin" value="{{ old('vin', $ttu->vin ?? '') }}" {{ !empty($readonly) ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer</label>
                        <input type="text" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $ttu->manufacturer ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $ttu->brand ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" id="model" name="model" value="{{ old('model', $ttu->model ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" id="year" name="year" value="{{ old('year', $ttu->year ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="status">TTU Status</label>
                        <select id="status" name="status" {{ !empty($readonly) ? 'disabled' : '' }}>
                            <option value="" {{ old('status', $ttu->status ?? '') == '' ? 'selected' : '' }}>None</option>
                            <option value="Not Ready for Occupancy (#b22222)" {{ old('status', $ttu->status ?? '') == 'Not Ready for Occupancy (#b22222)' ? 'selected' : '' }}>🔴 Not Ready for Occupancy</option>
                            <option value="Ready for Occupancy (#228b22)" {{ old('status', $ttu->status ?? '') == 'Ready for Occupancy (#228b22)' ? 'selected' : '' }}>🟢 Ready for Occupancy</option>
                            <option value="Demobilized (#ffd700)" {{ old('status', $ttu->status ?? '') == 'Demobilized (#ffd700)' ? 'selected' : '' }}>🟡 Demobilized</option>
                            <option value="Ready for Transport (#F7630C)" {{ old('status', $ttu->status ?? '') == 'Ready for Transport (#F7630C)' ? 'selected' : '' }}>🟠 Ready for Transport</option>
                            <option value="Transferred to Auction (#007bff)" {{ old('status', $ttu->status ?? '') == 'Transferred to Auction (#007bff)' ? 'selected' : '' }}>🔵 Transferred to Auction</option>
                            <option value="Transferred to City/County/State Entity (#800080)" {{ old('status', $ttu->status ?? '') == 'Transferred to City/County/State Entity (#800080)' ? 'selected' : '' }}>🟣 Transferred to City/County/State Entity</option>
                        </select>
                    </div>
                </div>

                <!-- Title Info -->
                <div class="form-row">
                    <div class="form-group form-group-title">Title Manufacturer, Brand, Model:</div>
                    <div class="form-group">
                        <input type="text" name="title_manufacturer" placeholder="Enter manufacturer" value="{{ old('title_manufacturer', $ttu->title_manufacturer ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <input type="text" name="title_brand" placeholder="Enter brand" value="{{ old('title_brand', $ttu->title_brand ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <input type="text" name="title_model" placeholder="Enter model" value="{{ old('title_model', $ttu->title_model ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group" style="display:flex;">
                        <label for="title-select" style="margin-right: 5px; font-size: 13px;">Do we have the Title?</label>
                        <select id="title-select" name="has_title" style="flex:2;" {{ !empty($readonly) ? 'disabled' : '' }}>
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option {{ old('has_title', $ttu->has_title ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <!-- Location & Details -->
                <div class="form-row">
                    <!-- Location Type Selector -->
                    <div class="form-group">
                        <label for="location_type">Location Type</label>
                        <select id="location_type" name="location_type" {{ !empty($readonly) ? 'disabled' : '' }}>
                            <option value="">Select Type</option>
                            <option value="hotel" {{ old('location_type', $ttu->location_type ?? '') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                            <option value="statepark" {{ old('location_type', $ttu->location_type ?? '') == 'statepark' ? 'selected' : '' }}>State Park</option>
                            <option value="privatesite" {{ old('location_type', $ttu->location_type ?? '') == 'privatesite' ? 'selected' : '' }}>Private Site</option>
                        </select>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $ttu->location ?? '') }}" autocomplete="off" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                        <div id="location-suggestions" class="autocomplete-suggestions"></div>
                    </div>
                    <div class="form-group" id="unit_num-group" style="position: relative;">
                        <label for="unit_num">Unit #</label>
                        <input type="text" id="unit_num" name="unit_num"
                            value="{{ old('unit_num', $ttu->unit_num ?? '') }}"
                            autocomplete="off"
                            @if((old('location_type', $ttu->location_type ?? '') !== 'statepark') || !empty($readonly))
                                disabled readonly
                            @endif
                        >
                        <div id="unit-num-suggestions" class="autocomplete-suggestions"></div>
                    </div>
                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" id="county" name="county" value="{{ old('county', $ttu->county ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="imei">IMEI# (GPS)</label>
                        <input type="text" id="imei" name="imei" value="{{ old('imei', $ttu->imei ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                </div>
                <div class="form-row">                    
                    
                    <div class="form-group" style="flex: 0 0 auto;">
                        <label for="key_no">TTU Key #</label>
                        <input type="text" id="key_no" name="key_no" value="{{ old('key_no', $ttu->key_no ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }} maxlength="5" pattern="[A-Za-z0-9]{5}" inputmode="latin" title="Enter 5 letters or numbers" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '').slice(0,5)">
                    </div>
                    <div class="form-group">
                        <label for="li_date">License In Date</label>
                        <input type="date" id="li_date" name="li_date" value="{{ old('li_date', $ttu->li_date ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price (USD)</label>
                        <div style="position:relative;">
                            <span aria-hidden="true" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#777;">$</span>
                            <input type="text" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $ttu->purchase_price ?? '') }}" style="padding-left:22px;" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                        </div>
                    </div>
                    <div class="form-group" style="position: relative;">
                        <label for="purchase_origin">Purchase Origin</label>
                        <input type="text" id="purchase_origin" name="purchase_origin" value="{{ old('purchase_origin', $ttu->purchase_origin ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                        <div id="purchase-origin-suggestions" class="autocomplete-suggestions"></div>
                    </div>
                    <div class="form-group">
                        <label for="total_beds">Total beds</label>
                        <input type="number" id="total_beds" name="total_beds" value="{{ old('total_beds', $ttu->total_beds ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 0 0 auto;">
                        <label for="condition_code">Condition code</label>
                        <select id="condition_code" name="condition_code" {{ !empty($readonly) ? 'disabled' : '' }} style="width: 140px;">
                            <option value="">Select condition code</option>
                            <option value="A1" {{ old('condition_code', $ttu->condition_code ?? '') == 'A1' ? 'selected' : '' }}>A1</option>
                            <option value="A2" {{ old('condition_code', $ttu->condition_code ?? '') == 'A2' ? 'selected' : '' }}>A2</option>
                            <option value="A3" {{ old('condition_code', $ttu->condition_code ?? '') == 'A3' ? 'selected' : '' }}>A3</option>
                            <option value="A4" {{ old('condition_code', $ttu->condition_code ?? '') == 'A4' ? 'selected' : '' }}>A4</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 0 0 auto;">
                        <label for="weather_radio">Weather Radio</label>
                        <input type="text" id="weather_radio" name="weather_radio" value="{{ old('weather_radio', $ttu->weather_radio ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }} style="width: 140px;">
                    </div>
                    <div class="form-group" style="flex:2;">
                        <label for="fdec_id">FDEC</label>
                        @php
                            $selectedFdec = old('fdec_id', $ttu->fdec_id ?? []);
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
                </div>
                <div id="privatesite-section" class="container form-section" style="display: {{ old('privatesite', $ttu->privatesite ?? false) ? 'flex' : 'none' }};">
                    <div class="column">
                        <div>
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $privatesite->name ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $privatesite->address ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $privatesite->phone ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
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
                                <textarea id="damage_assessment" name="damage_assessment" {{ !empty($readonly) ? 'readonly disabled' : '' }}>{{ old('damage_assessment', $privatesite->damage_assessment ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ehp">EHP:</label>
                                <input type="text" id="ehp" name="ehp" value="{{ old('ehp', $privatesite->ehp ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="ehp_notes">EHP Notes:</label>
                                <textarea id="ehp_notes" name="ehp_notes" {{ !empty($readonly) ? 'readonly disabled' : '' }}>{{ old('ehp_notes', $privatesite->ehp_notes ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dow_long">DOW Longitude:</label>
                                <input type="text" id="dow_long" name="dow_long" value="{{ old('dow_long', $privatesite->dow_long ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="dow_lat">DOW Latitude:</label>
                                <input type="text" id="dow_lat" name="dow_lat" value="{{ old('dow_lat', $privatesite->dow_lat ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="form-group">
                                <label for="zon">Zoning:</label>
                                <input type="text" id="zon" name="zon" value="{{ old('zon', $privatesite->zon ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="dow_response">DOW Response:</label>
                                <textarea id="dow_response" name="dow_response" {{ !empty($readonly) ? 'readonly disabled' : '' }}>{{ old('dow_response', $privatesite->dow_response ?? '') }}</textarea>
                            </div>                                    
                        </div>                           
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
                                        <input type="hidden" name="{{ $field }}" value="0">
                                        <input type="checkbox" name="{{ $field }}" value="1"
                                            {{ old($field, $ttu->$field ?? 0) == 1 ? 'checked' : '' }}
                                            {{ !empty($readonly) ? 'disabled' : '' }}>
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
                                        <input type="hidden" name="{{ $field }}" value="0">
                                        @if($field === 'is_being_donated')
                                            <input type="checkbox" name="{{ $field }}" value="1"
                                                {{ old($field, $is_being_donated ?? 0) == 1 ? 'checked' : '' }}
                                                {{ !empty($readonly) ? 'disabled' : '' }}>
                                        @elseif($field === 'is_sold_at_auction')
                                            <input type="checkbox" name="{{ $field }}" value="1"
                                                {{ old($field, $is_sold_at_auction ?? 0) == 1 ? 'checked' : '' }}
                                                {{ !empty($readonly) ? 'disabled' : '' }}>
                                        @else
                                            <input type="checkbox" name="{{ $field }}" value="1"
                                                {{ old($field, $ttu->$field ?? 0) == 1 ? 'checked' : '' }}
                                                {{ !empty($readonly) ? 'disabled' : '' }}>
                                        @endif
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
                                <label for="disposition">Disposition</label>
                                <select id="disposition" name="disposition" {{ !empty($readonly) ? 'disabled' : '' }}>
                                    <option value="" {{ old('disposition', $ttu->disposition ?? '') == '' ? 'selected' : '' }}>-- Select Disposition --</option>    
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
                                    <option value="Vacant" {{ old('disposition', $ttu->disposition ?? '') == 'Vacant' ? 'selected' : '' }}>Vacant</option>
                                    <option value="Unknown" {{ old('disposition', $ttu->disposition ?? '') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="transpo_agency">Transport Agency</label>
                                <input type="text" id="transpo_agency" name="transpo_agency" value="{{ old('transpo_agency', $ttu->transpo_agency ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                            </div>
                        </div>
                        <!-- Donation Section -->
                        <div id="donation-section" class="form-section" style="margin-bottom: 10px; display: none;">
                            <div class="form-group">
                                <label>Is the recipient a State, City, County, or NPO?</label>
                                <select name="recipient_type" id="recipient_type" {{ !empty($readonly) ? 'disabled' : '' }}>
                                    <option value="">-- Select --</option>
                                    <option value="yes" {{ (isset($transfer->recipient_type) && strtolower($transfer->recipient_type) === 'yes') || (isset($ttu->transfer['recipient_type']) && strtolower($ttu->transfer['recipient_type']) === 'yes') ? 'selected' : '' }}>Yes</option>
                                    <option value="no"  {{ (isset($transfer->recipient_type) && strtolower($transfer->recipient_type) === 'no')  || (isset($ttu->transfer['recipient_type']) && strtolower($ttu->transfer['recipient_type']) === 'no')  ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-row margin-bottom-none">
                                <div class="form-group">
                                    <label for="donation_agency">What Agency Is being given to?</label>
                                    <input type="text" id="donation_agency" name="donation_agency" value="{{ old('donation_agency', $transfer->donation_agency ?? '') }}" style="max-width: 300px;" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                </div>
                                <div class="form-group">
                                    <label for="donation_category">Donation Category</label>
                                    <select id="donation_category" name="donation_category" {{ !empty($readonly) ? 'disabled' : '' }}>
                                        <option value="" {{ old('donation_category', $transfer->donation_category ?? '') == '' ? 'selected' : '' }}>-- Select Category --</option>
                                        <option value="State" {{ old('donation_category', $transfer->donation_category ?? '') == 'State' ? 'selected' : '' }}>State</option>
                                        <option value="County" {{ old('donation_category', $transfer->donation_category ?? '') == 'County' ? 'selected' : '' }}>County</option>
                                        <option value="City" {{ old('donation_category', $transfer->donation_category ?? '') == 'City' ? 'selected' : '' }}>City</option>
                                        <option value="Non-Profit" {{ old('donation_category', $transfer->donation_category ?? '') == 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sold At Auction Section -->
                        <div id="sold-at-auction-section" class="form-section" style="margin-bottom: 10px; display: none;">
                            <div class="form-row margin-bottom-none">
                                <div class="form-group">
                                    <label for="sold_at_auction_price">Sold At Auction Price</label>
                                    <div style="position:relative;">
                                        <span aria-hidden="true" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#777;">$</span>
                                        <input type="text" id="sold_at_auction_price" name="sold_at_auction_price" value="{{ old('sold_at_auction_price', $transfer->sold_at_auction_price ?? '') }}" style="padding-left:22px;" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient">Who is recipient?</label>
                                    <input type="text" id="recipient" name="recipient" value="{{ old('recipient', $transfer->recipient ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="form-section">    
                            <div class="remarks">
                                <div>
                                    <label for="remarks">Remarks</label>
                                    <textarea id="remarks" name="remarks" {{ !empty($readonly) ? 'readonly disabled' : '' }}>{{ old('remarks', $ttu->remarks ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label for="comments">Comments/Notes</label>
                                    <textarea id="comments" name="comments" {{ !empty($readonly) ? 'readonly disabled' : '' }}>{{ old('comments', $ttu->comments ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Assigned Survivor -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-section" style="margin-top: 20px;">
                            <h4>Assigned Survivor</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fema_id">FEMA-ID</label>
                                    <div style="display:flex; gap:4px;">
                                        <div style="position:relative;">
                                            <input type="text" id="fema_id" name="fema_id" autocomplete="off" value="{{ old('fema_id', $selectedFemaId ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                            <div id="fema-id-suggestions" class="autocomplete-suggestions"></div>
                                        </div>
                                        <button class="btn btn-primary" type="button"
                                            @if(!empty($ttu) && !empty($ttu->survivor_id))
                                                onclick="window.location.href='{{ route('admin.survivors.view', $ttu->survivor_id) }}'"
                                            @else
                                                disabled
                                            @endif
                                        >
                                            Go-to Record
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="survivor_name">Name</label>
                                    <div style="position:relative;">
                                        <input type="text" id="survivor_name" name="survivor_name" value="{{ $survivor_name }}" autocomplete="off" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                        <div id="survivor-name-suggestions" class="autocomplete-suggestions"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="survivor_id" name="survivor_id" value="{{ old('survivor_id', $ttu->survivor_id ?? '') }}">
                                <div class="form-group" style="flex:1;">
                                    <label for="li_date">LI Date</label>
                                    <input type="date" id="li_date" name="li_date" value="{{ old('li_date', $ttu->li_date ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label for="lo-date">LO Date</label>

                                    <input type="date" id="lo_date" name="lo_date" value="{{ old('lo_date', $ttu->lo_date ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label for="est_lo_date">Est. LO Date</label>
                                    <input type="date" id="est_lo_date" name="est_lo_date" value="{{ old('est_lo_date', $ttu->est_lo_date ?? '') }}" {{ !empty($readonly) ? 'readonly disabled' : '' }}>
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
                                @if(!empty($readonly))
                                    <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.ttus') }}';" style="margin-right: 16px;">Back</button>
                                    <a href="" class="btn btn-save">Show History</a>
                                @else
                                    <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.ttus') }}';" style="margin-right: 16px;">Cancel</button>
                                    <button type="submit" class="btn btn-save">{{ isset($ttu) ? 'Update' : 'Save' }}</button>
                                @endif
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
