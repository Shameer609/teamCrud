<!-- Modal Header -->
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Edit Team</h4>
  </div>  
  <form action="/crm/team/update" method="POST">
      <!-- Modal body -->
      <div class="modal-body">
          <div class="row">
              @csrf
              <input type="hidden" name="id" value="{{ $team->id }}">
              <div class="col-sm-12">
                  <div class="form-group">
                      <label>Department</label>
                      <select class="form-control select2 department_id" name="department_id">
                          <option selected disabled>Select Please</option>
                          @foreach ($department as $item)
                              <option value="{{ $item['id'] }}" {{ ($item['id'] == $team->department_id) ? 'selected' : '' }}>{{ $item['name'] }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="col-sm-12">
                  <div class="form-group">
                      <label>Name</label>
                      <input type="text" name="name" class="form-control" id="name" value="{{ $team->name }}">
                  </div>
              </div>

              <div class="col-sm-12">
                  <div class="form-group">
                      <label>Team Lead</label>
                      <select class="form-control select2" name="team_lead" id="team_lead">
                          <option selected disabled>Select Please</option>
                          @foreach ($members as $item)
                              <option value="{{ $item['id'] }}" {{ ($item['id'] == $team->team_lead) ? 'selected' : '' }}>{{ $item['username'] }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="col-sm-12">
                  <div class="form-group">
                      <label>Team Members</label>
                      <select class="form-control select2" multiple name="team_member[]" id="team_member">
                        @foreach ($members as $item)
                            @foreach ($team_members as $tm)
                                <option value="{{ $item['id'] }}" {{ ($item['id'] == $tm->member) ? 'selected' : '' }}>{{ $item['username'] }}</option>
                            @endforeach
                        @endforeach
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