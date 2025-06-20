@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/editPage.css') }}">
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="DetailedSearch">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <form method="GET" action="{{ route('admin.detailedSearch') }}" class="search-form">
                <div class="form-row" style="display: flex; gap: 24px; align-items: flex-end;">
                    <div class="form-group" style="flex: 1;">
                        <label for="scope">Scope</label>
                        <select id="scope" name="scope" class="table-select">
                            <option value="all" {{ request('scope', $scope ?? 'all') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="survivors" {{ request('scope', $scope ?? '') == 'survivors' ? 'selected' : '' }}>Survivors</option>
                            <option value="ttus" {{ request('scope', $scope ?? '') == 'ttus' ? 'selected' : '' }}>TTUs</option>
                            <option value="locations" {{ request('scope', $scope ?? '') == 'locations' ? 'selected' : '' }}>Locations</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="table-input" placeholder="Type Here" style="flex: 1;" value="{{ request('keyword', $keyword ?? '') }}">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="count_by">Count By (Field)</label>
                        <select id="count_by" name="count_by" class="table-select">
                            <option value="" {{ request('count_by', $count_by ?? '') == '' ? 'selected' : '' }}>Select Option</option>
                            <option value="scope" {{ request('count_by', $count_by ?? '') == 'scope' ? 'selected' : '' }}>Scope</option>
                            <option value="name" {{ request('count_by', $count_by ?? '') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="address" {{ request('count_by', $count_by ?? '') == 'address' ? 'selected' : '' }}>Address</option>
                            <option value="phone" {{ request('count_by', $count_by ?? '') == 'phone' ? 'selected' : '' }}>Phone Number</option>
                            <option value="author" {{ request('count_by', $count_by ?? '') == 'author' ? 'selected' : '' }}>Author</option>
                            <!-- Add other options as needed -->
                        </select>
                    </div>
                </div>
                @php
                    $filterFields = request()->input('filter_field', []);
                    $filterValues = request()->input('filter_value', []);
                    $filterCount = max(1, count($filterFields));
                @endphp

                <div id="sub-filter-group">
                    @for($i = 0; $i < $filterCount; $i++)
                        <div id="sub-filter_{{ $i }}" class="form-row" style="display: flex; gap: 12px; align-items: center; margin-top: 18px;">
                            <span><i class="fa fa-arrow-right" style="font-size: 1.2em;"></i></span>
                            <select name="filter_field[]" class="table-select" style="width: 120px;">
                                <option value="">Select Option</option>
                                <option value="name" {{ ($filterFields[$i] ?? '') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="address" {{ ($filterFields[$i] ?? '') == 'address' ? 'selected' : '' }}>Address</option>
                                <option value="phone" {{ ($filterFields[$i] ?? '') == 'phone' ? 'selected' : '' }}>Phone</option>
                                <option value="author" {{ ($filterFields[$i] ?? '') == 'author' ? 'selected' : '' }}>Author</option>
                            </select>
                            <input type="text" name="filter_value[]" class="table-input" placeholder="Enter value" style="flex: 1;"
                                value="{{ $filterValues[$i] ?? '' }}">
                            <button type="button" class="remove-sub-filter" style="background: none; border: none; ">
                                <i class="fa fa-trash" style="font-size: 1.2em;"></i>
                            </button>
                        </div>
                    @endfor
                </div>

                <div class="form-row" style="margin-top: 8px; justify-content: space-between">
                    <button type="button" id="add-filter" class="table-btn" style="background: none; border: none;">
                        + Add Filter
                    </button>
                    <button type="submit" class="search-button table-btn" >
                        Search
                    </button>
                </div>
            </form>

            <table id="detailed-search-table" class="styled-table">
                <thead id="dynamic-table-header">
                    <!-- Dynamic header will be rendered here -->
                </thead>
                <tbody id="dynamic-table-body">
                    <!-- Dynamic body will be rendered here -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    const detailedSearchData = @json($results);
    const detailedSearchColumns = @json($columns);
</script>
<script src="{{ asset('js/detailedSearchTable.js') }}"></script>

@endsection
