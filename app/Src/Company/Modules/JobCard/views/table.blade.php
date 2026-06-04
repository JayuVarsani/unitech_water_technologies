
@if($user->assign_role_name=='Machine operator')
@include('company::JobCard.views.machinejob')
@else

@include('company::JobCard.views.commonjob')

@endif
