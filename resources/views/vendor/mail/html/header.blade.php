@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{asset(mix('build/panel/images/logo/logo.png'))}}" class="logo" alt="{{config('app.name')}} Logo">
{{--            {{ $slot }}--}}
        </a>
    </td>
</tr>
