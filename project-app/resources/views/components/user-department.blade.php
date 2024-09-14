@props(['deptId'])

@php
    switch ($deptId) {
        case 1:
            $department = 'IT';
            break;
        case 2:
            $department = 'Sales';
            break;
        case 3:
            $department = 'Fleet';
            break;
        case 4:
            $department = 'Production';
            break;
        default:
            $department = 'Unknown';
    }
@endphp

<span>{{ $department }}</span>
