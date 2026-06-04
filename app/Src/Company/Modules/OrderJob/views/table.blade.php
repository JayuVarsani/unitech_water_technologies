
@if($user->assign_role_name=='Machine operator')
@include('company::OrderJob.views.machinejob')
@else

@include('company::OrderJob.views.commonjob')

@endif
