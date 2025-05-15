@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="\plugins\datatables\dataTables.bootstrap.css">
    <style>
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            align-items: center;
        }
        .form-group {
            flex: 1 1 160px;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group-title {
            flex: 1 1 90px;
            color: gray;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .status-group {
            display: flex;
            align-items: center;
            flex: 1 1 200px;
        }
        .status-group select {
            background-color: #e7f0fa; /* Light blue background */
        }
    </style>

    <style>
        .container {
            display: flex;
            gap: 20px;
            margin-top: 50px
        }

        .column {
            flex: 3;
            min-width: 200px;
        }

        .form-column {
            flex: 7;
            min-width: 360px;
        }

        table {
            width: 100%;
            border-spacing: 0 10px;
        }

        .table-header {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        td {
            padding: 5px 0;
            border-bottom: 1px solid #ccc;
        }

        .form-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .remarks {
            display: flex;
            gap: 20px;
        }

        .remarks div {
            flex: 1;
        }

        textarea {
            width: 100%;
            height: 60px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(19px);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .info {
            font-size: 14px;
            color: gray;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-cancel {
            background-color: #ddd;
            color: black;
            text-decoration: underline;
        }

        .btn-save {
            background-color: #007bff;
            color: white;
        }
    </style>
@endsection

@section('content')
<section id="Global">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-row">
                <div class="form-group">
                    <label for="vin">VIN</label>
                    <input type="text" id="vin" value="5ZT2CXSBXSM090391">
                </div>
                <div class="form-group">
                    <label for="manufacturer">Manufacturer</label>
                    <select id="manufacturer">
                        <option>Forest River</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <select id="brand">
                        <option>Apex Ultra Lite</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" id="model" value="265RBSS">
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="text" id="year" value="2019">
                </div>
                <div class="form-group status-group">
                    <label for="status">TTU Status</label>
                    <select id="status">
                        <option>Blue (Transferred)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-group-title">Title Manufacturer, Brand, Model:</div>
                <div class="form-group">
                    <input type="text" placeholder="Enter manufacturer">
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Enter brand">
                </div>
                <div class="form-group">
                    <input type="text" placeholder="Enter model">
                </div>
                <div class="form-group" style="display: flex; align-items: center; justify-content: flex-end;">
                    <label for="title-select" style="margin-right: 5px; font-size: 13px;">Do&nbsp;we&nbsp;have&nbsp;the&nbsp;Title?</label>
                    <select id="title-select">
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" value="Brexit County State Park of Florida">
                </div>
                <div class="form-group">
                    <label for="lot">Unit Loc./Lot #</label>
                    <input type="text" id="lot" value="Lot 102">
                </div>
                <div class="form-group">
                    <label for="county">County</label>
                    <select id="county">
                        <option>Brexit County</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="imei">IMEI# (GPS)</label>
                    <input type="text" id="imei" value="123456789123456">
                </div>
                <div class="form-group">
                    <label for="price">Purchase Price</label>
                    <input type="text" id="price" value="$30,000.00">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="container">
                <div class="column">
                    <span class="table-header">Does the TTU have:</span>
                    <table>
                        <tr>
                            <td>200+ SQFT</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Prop. Fireplace</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>TV</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Hydraulics</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Steps</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Teardrop Design</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Folding Walls</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Outdoor Kitchen</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                    </table>
                </div>

                <div class="column">
                    <span class="table-header">Is the TTU:</span>
                    <table>
                        <tr>
                            <td>Onsite</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Occupied</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Winterized</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Deblocked</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Cleaned</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>GPS Removed</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                        <tr>
                            <td>Being Donated</td>
                            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Sold at Auction</td>
                            <td><label class="switch"><input type="checkbox"><span class="slider"></span></label></td>
                        </tr>
                    </table>
                </div>

                <div class="form-column form-section">
                    <label for="disposition">Disposition:</label>
                    <select id="disposition">
                        <option>Officially Transferred</option>
                    </select>
                    <label for="transport">Transport Agency:</label>
                    <select id="transport">
                        <option>Select...</option>
                    </select>
                    <div class="status-group">
                        <label>Is the recipient a State, City, County, or NPO?</label>
                        <select>
                            <option>YES</option>
                        </select>
                    </div>
                    <label for="agency">What Agency Is being given to?</label>
                    <input type="text" id="agency" value="Brexit County Donation">
                    <label for="category">Donation Category:</label>
                    <input type="text" id="category" value="County">
                    <div class="remarks">
                        <div>
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks"></textarea>
                        </div>
                        <div>
                            <label for="comments">Comments/Notes:</label>
                            <textarea id="comments"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4>Assigned Survivor</h4>
            <div class="form-row">
                <div class="form-group">
                    <label for="fema">FEMA ID</label>
                    <input type="text" id="fema" value="Brexit County State Park of Florida">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" value="Lot 102">
                </div>
                <div class="form-group">
                    <label for="lo">LO</label>
                    <select id="lo">
                        <option>NO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lo-date">LO Date</label>
                    <input type="text" id="lo-date" value="N/A">
                </div>
                <div class="form-group">
                    <label for="est-lo-date">Est. LO Date</label>
                    <input type="text" id="est-lo-date" value="N/A">
                </div>
            </div>

            <div class="form-footer">
                <div class="info">
                    <div>Authored by:</div>
                    <div>Created: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Last Edited:</div>
                </div>
                <div class="buttons">
                    <button class="btn btn-cancel">Cancel</button>
                    <button class="btn btn-save">Save</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
        document.querySelectorAll('.options-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const dropdown = this.querySelector('.dropdown-menu');
                const isActive = dropdown.classList.contains('active');
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
                if (!isActive) {
                    dropdown.classList.add('active');
                }
            });
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.options-icon')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('active'));
            }
        });
</script>
@endsection
