@if($is_deleted === 0)
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
    <span>Active</span>
@else
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
    <span>Inactive</span>
@endif