<!-- resources/views/components/icons/sort-icon.blade.php -->
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 inline-block ml-1">
    @if($direction === 'asc')
        <!-- Display only upward arrow when ascending sort is active -->
        <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
    @elseif($direction === 'desc')
        <!-- Display only downward arrow when descending sort is active -->
        <path fill-rule="evenodd" d="M10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd"/>
    @else
        <!-- Display both arrows by default when no sorting is active -->
        <g>
            <!-- Upward arrow -->
            <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
            <!-- Downward arrow -->
            <path fill-rule="evenodd" d="M10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd"/>
        </g>
    @endif
</svg>
