@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .stats-container, .main-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .stat-box, .operations-table, .chart {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-box {
            text-align: center;
            flex: 1;
            max-width: 300px;
        }

        .stat-box h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .operations-table {
            flex: 1;
            overflow-x: auto;
        }

        .operations-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .operations-table th, .operations-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .chart {
            flex: 1;
            max-width: 600px;
        }

        @media (max-width: 768px) {
            .stat-box, .operations-table, .chart {
                flex: 0 0 100%;
                max-width: none;
            }
        }
    </style>
@endsection

@section('content')
<section id="Dashboard">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="stats-container">
                <div class="stat-box">
                    <h4>Total TTUs in Field</h4>
                    <h1>200</h1>
                    <p>+10% month over month</p>
                </div>
                <div class="stat-box">
                    <h4>Total Survivors</h4>
                    <h1>576</h1>
                    <p>+3% month over month</p>
                </div>
                <div class="stat-box">
                    <h4>Monthly Program Moveouts</h4>
                    <h1>10</h1>
                    <p>-8% month over month</p>
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
                            <tr>
                                <td>Barren River State Park</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Western RV Park</td>
                                <td>4</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>Hopkins County</td>
                                <td>3</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Muhlenberg County</td>
                                <td>2</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>Ramada Inn</td>
                                <td>26</td>
                                <td>12</td>
                            </tr>
                            <tr>
                                <td>1234 Fake Rd.</td>
                                <td>2</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>The Pentagon</td>
                                <td>0</td>
                                <td>1</td>
                            </tr>
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

<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: '# of Moveouts',
                data: [1, 2, 3, 4, 9, 10, 7, 6, 5, 2, 1, 0],
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });
</script>
@endsection
