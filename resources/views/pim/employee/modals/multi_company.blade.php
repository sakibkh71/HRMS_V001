<!-- showData modal start -->
<div class="modal fade bs-example-modal-lg showMultiCompanyData" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal department-create" action="{{url('employee/updateCompanys')}}" method="post" id="department-create">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Parents/Sister Companys</h4>
                </div>
                <div class="modal-body">
                    <div id="create-form-errors">
                    </div>
                    {{ csrf_field() }}

                    <input type="hidden" value="" name="hdn_id" class="hdn_id">
                    <input type="hidden" value="" name="hdn_email" class="hdn_email">

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                        @if(count($motherNsisters) > 0)
                            @foreach($motherNsisters as $info)
                                <div class="row">
                                    <input type="hidden" name="user_companys[{{$info->id}}]" value="0">
                                    <input type="checkbox" name="user_companys[{{$info->id}}]" value="{{$info->id}}"> 
                                    {{$info->company_name}} 
                                </div>     
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(in_array($chkUrl."/edit", session('userMenuShare')))
                        <button type="submit" class="btn btn-primary">Update Leave</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<!-- showData modal end -->  