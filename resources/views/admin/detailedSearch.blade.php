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
                            <option value="all">All</option>
                            <option value="survivors">Survivors</option>
                            <option value="ttus">TTUs</option>
                            <option value="locations">Locations</option>
                            <!-- Add other options as needed -->
                        </select>
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="table-input" placeholder="Type Here" style="flex: 1;">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="count_by">Count By (Field)</label>
                        <select id="count_by" name="count_by" class="table-select">
                            <option value="author">Author</option>
                            <!-- Add other options as needed -->
                        </select>
                    </div>
                </div>
                <div class="form-row" style="display: flex; gap: 12px; align-items: center; margin-top: 18px;">
                    <span><i class="fa fa-arrow-right" style="font-size: 1.2em;"></i></span>
                    <select name="filter_field[]" class="table-select" style="width: 120px;">
                        <option value="address">Address</option>
                        <!-- Add other filter fields as needed -->
                    </select>
                    <input type="text" name="filter_value[]" class="table-input" value="5309 Aerosmith Rd." placeholder="Enter value" style="flex: 1;">
                </div>
                <div class="form-row" style="margin-top: 8px; justify-content: space-between">
                    <button type="button" class="table-btn" style="background: none; border: none;">
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
