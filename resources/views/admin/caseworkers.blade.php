@extends('template.template')

@section('content-header')
<link rel="stylesheet" href="{{ asset('css/tablePage.css') }}">
@endsection

@section('content')
<section id="Caseworkers">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="header">
                <div class="search-container">
                    <input type="text" id="search-input" placeholder="Search">
                    <button id="search-button" class="filter-icon"><i class="fa fa-filter"></i></button>
                </div>
                <button class="add-new-button btn btn-primary" data-toggle="modal" data-target="#addCaseworkerModal" >
                    Add New
                </button>
            </div>
            <!-- Add New Button -->
            
            <table class="table" id="caseworkers-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @if(isset($caseworkers))
                        @foreach($caseworkers as $cw)
                            <tr>
                                <td>{{ $cw->id }}</td>
                                <td>{{ $cw->fname }} {{ $cw->lname }}</td>
                                <!-- Add more fields as needed -->
                                <td>
                                    <button type="button"
                                        class="edit-caseworker-btn"
                                        style="background: none; border: none;"
                                        data-toggle="modal"
                                        data-target="#addCaseworkerModal"
                                        data-id="{{ $cw->id }}"
                                        data-fname="{{ $cw->fname }}"
                                        data-lname="{{ $cw->lname }}">
                                        <i class="fa fa-edit" style="font-size: 1.2em;"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.caseworkers.destroy', $cw->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none;"
                                            onclick="return confirm('Are you sure you want to delete this caseworker?')">
                                            <i class="fa fa-trash" style="font-size: 1.2em;"></i>
                                        </button>
                                        <!-- <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this caseworker?')">
                                            Delete
                                        </button> -->
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Add/Edit Caseworker Modal -->
<div class="modal fade" id="addCaseworkerModal" tabindex="-1" role="dialog" aria-labelledby="addCaseworkerModalLabel">
  <div class="modal-dialog" role="document">
    <form id="caseworker-form" method="POST" action="{{ route('admin.caseworkers.store') }}">
      @csrf
      <input type="hidden" name="id" id="caseworker-id">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px; font-size: 2rem; z-index: 10;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="addCaseworkerModalLabel">Add New Caseworker</h4>
        </div>
        <div class="modal-body">
            <div class="form-row" style="display: flex; gap: 10px;">
                <div class="form-group" style="flex:1;">
                    <label for="fname">First Name</label>
                    <input id="fname" type="text" class="form-control" name="fname" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label for="lname">Last Name</label>
                    <input id="lname" type="text" class="form-control" name="lname" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="save-caseworker-btn" class="btn btn-primary">Add Caseworker</button>
          <button type="submit" id="update-caseworker-btn" class="btn btn-success" style="display:none;">Update Caseworker</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="{{ asset('js/caseworkers.js') }}"></script>

@endsection
