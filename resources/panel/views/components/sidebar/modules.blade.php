<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
     id="kt_app_sidebar_menu"
     data-kt-menu="true"
     data-kt-menu-expand="false">
    
    @foreach($modules as $module)
        @if(array_key_exists('children',$module) && count($module['children']))
            <div data-kt-menu-trigger="click" 
                @class([
                'menu-item',
                'menu-accordion',
                'show'=>in_array(request()->route()->getName(),collect($module['children'])->pluck('sub_routes')->flatten()->toArray())
                ])>
                <span class="menu-link">
                    <span class="menu-icon">{!! $module['icon'] !!}</span>
                    <span class="menu-title">{{ucwords($module['name'])}}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    @if(!empty($module['children']))
                        @foreach($module['children'] as $childModule)
                            @if(auth()->user()->type == 'staff' && $childModule['unique_name'] == 'company.role-permission')
                            @else
                                <div class="menu-item">
                                    <a @class(['menu-link','active'=>in_array(request()->route()->getName(),$childModule['sub_routes']??[])])
                                    href="{{$childModule['index_route']?route($childModule['index_route']):"javascript:void()"}}">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">{{$childModule['name']}}</span>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @else
            <div class="menu-item">
                <a @class(['menu-link','active'=>in_array(request()->route()->getName(),$module['sub_routes']??[])])
                   href="{{$module['index_route']?route($module['index_route']):"javascript:void()"}}">
                    <span class="menu-icon">{!! $module['icon'] !!}</span>
                    <span class="menu-title">{{ucwords($module['name'])}}</span>
                </a>
            </div>
        @endif
    @endforeach
    @livewire('panel.logout-link', ['variant' => 'sidebar'])
</div>

