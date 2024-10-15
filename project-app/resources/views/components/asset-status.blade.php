@if ($status === 'active')
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
    <span>Active</span>
@elseif ($status === 'deployed')
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
    <span>Deployed</span>
@elseif ($status === 'under_maintenance')
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
    <span>Under Maintenance</span>
@elseif ($status === 'disposed')
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-gray-500"></span>
    <span>Disposed</span>
@else
    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
    <span>Unknown Status</span>
@endif