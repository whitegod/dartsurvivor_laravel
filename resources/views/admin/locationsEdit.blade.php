@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
@endsection

@section('content')
<section id="EditLocation">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 0 auto;">
            <form method="POST" action="{{ isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store') }}">
                @csrf
                <div class="form-row" style="display: flex; gap: 24px; margin-bottom: 24px;">
                    @if(isset($location))
                        <input type="hidden" name="type" value="{{ $type }}">
                    @else
                        <div class="form-group" style="flex: 1;">
                            <label for="type">Location Type</label>
                            <select name="type" id="type"required>
                                <option value="">Select Type</option>
                                <option value="Hotel" {{ old('type') == 'Hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="State Park" {{ old('type') == 'State Park' ? 'selected' : '' }}>State Park</option>
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
        </div>
    </div>
</section>
@endsection