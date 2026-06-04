<tbody class="text-gray-800">
@if($items->count())
    {{$slot}}
@else
    <tr><td class="text-center" colspan="100%">{{__('app.panel.table.no_record_found')}}</td></tr>
@endif
</tbody>
