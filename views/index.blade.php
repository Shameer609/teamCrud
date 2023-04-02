@extends('layouts.app')
@section('title', 'Team')
@section('content')
@include('crm::layouts.nav')
<style>
    .form-group .select2{
        width: 100% !important;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header no-print">
   <h1>Teams</h1>
</section>

<section class="content no-print">
	@component('components.widget', ['class' => 'box-primary', 'title' => 'All Teams'])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-sm btn-primary btn-add-lead pull-right m-5" data-toggle="modal" data-target="#add_team">
                    <i class="fa fa-plus"></i> @lang('messages.add')
                </button>
            </div>
        @endslot

        <table class="table table-bordered table-striped ajax_view" id="purchase_order_table" style="width: 100%;">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Team Name</th>
                  <th>Department Name</th>
                  <th>Team Lead</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($team as $item)
               <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{!! $item->team !!}</td>
                  <td>{!! $item->department !!}</td>
                  <td>{!! $item->team_lead !!}</td>                  
                  <td>
                     <button class="btn btn-xs btn-primary" id="edit-btn" data-id="{{ $item->id }}"><i class="glyphicon glyphicon-edit"></i> Edit</button>
                     <button class="btn btn-xs btn-danger" data-href="{{ url('crm/team/delete', ['id' => $item->id]) }}" id="delete-btn"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                  </td>
                  @endforeach
            </tbody>
         </table>


        
    @endcomponent    
    

<!-- Add Modal -->
<div class="modal" id="add_team">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Team</h4>
        </div>  
        <form action="/crm/team/create" method="POST">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    @csrf
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Department</label>
                            <select class="form-control select2 department_id" name="department_id">
                                <option selected disabled>Select Please</option>
                                @foreach ($department as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Team Lead</label>
                            <select class="form-control select2" name="team_lead" id="team_lead">
                                <option selected disabled>Select Please</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Team Members</label>
                            <select class="form-control select2" multiple name="team_member[]" id="team_member">
                            </select>
                        </div>
                    </div>


                </div>
            </div>  
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div> 
        </form> 
      </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="edit_team">
    <div class="modal-dialog">
      <div class="modal-content">        
    </div>
</div>


</section>
@endsection
@section('javascript')
	<script src="{{ asset('modules/crm/js/crm.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('.ajax_view').DataTable();

            $(document).on('change', '.department_id' , function(){
                var id = $(this).val();
                $.ajax({
                    url: "/crm/get_members/" + id,
                    type: "GET",
                    datatype: "JSON",
                    success: function(data){
                        $('#team_lead, #team_member').html('<option disabled>Select Please</option>');
                        $.each(data, function(key, val){
                            $('#team_lead, #team_member').append('<option value="'+ val.id +'">'+ val.username +'</option>');
                        });
                            $('#edit_team #team_lead , #team_member').select2('destroy');
                            $('#edit_team #team_lead , #team_member').select2();
                    }
                });
            })

            $(document).on('click','#edit-btn', function(){
                var id = $(this).data('id');
                $.ajax({
                    url: "/crm/team/edit/" + id,
                    type: "GET",
                    success: function(data){
                    $('#edit_team .modal-content').html(data);
                    $('#edit_team .select2').select2();
                    $('#edit_team').modal('show');
                    }
                });
            })
            
            $(document).on('click','#delete-btn', function(){
                swal({
                title: 'Are you sure ?',
                text: 'This team will be deleted',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        window.location.href = href;
                    }
                });
            })

            
        });
    </script>
@endsection