@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
@endsection

@section('content')
<section id="Dashboard">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="stats-container">
                <div class="stat-box">
                    <h4>Total TTUs in Field</h4>
                    <h1>{{ $totalTtus ?? 0 }}</h1>
                    <p>
                        @if(isset($ttusPercentChange))
                            {{ $ttusPercentChange > 0 ? '+' : '' }}{{ $ttusPercentChange }}% month over month
                        @else
                            N/A month over month
                        @endif
                    </p>
                </div>
                <div class="stat-box">
                    <h4>Total Survivors</h4>
                    <h1>{{ $totalSurvivors }}</h1>
                    <p>
                        @if(isset($survivorsPercentChange))
                            {{ $survivorsPercentChange > 0 ? '+' : '' }}{{ $survivorsPercentChange }}% month over month
                        @else
                            N/A month over month
                        @endif
                    </p>
                </div>
                <div class="stat-box">
                    <h4>Monthly Program Moveouts</h4>
                    <h1>{{ $monthlyMoveouts ?? 0 }}</h1>
                    <p>
                        @if(isset($moveoutsPercentChange))
                            {{ $moveoutsPercentChange > 0 ? '+' : '' }}{{ $moveoutsPercentChange }}% month over month
                        @else
                            N/A month over month
                        @endif
                    </p>
                </div>
            </div>

            <div class="main-content">
                <div class="operations-table">
                    <h3>Current Operating Picture</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Site</th>
                                <th>Onsite</th>
                                <th>Occupied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allLocations as $location)
                                <tr>
                                    <td>{{ $location->name }}</td>
                                    <td>{{ $location->onsite }}</td>
                                    <td>{{ $location->occupied }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="chart">
                    <h3>Total Program Moveouts</h3>
                    <canvas id="myChart"></canvas>
                </div>
            </div>            
        </div>
    </div>
</section>
<script>
    window.moveoutsPerMonth = @json($moveoutsPerMonth);
</script>
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/barchart.js') }}"></script>
@endsection
