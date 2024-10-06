{{-- @props(['data' => [] ]) --}}
{{-- @props(['activeTab']); --}}

{{-- @php
    $column = $activeTab === 'customField' ? ['name', 'type', 'Help Text'] : ['name', 'Description'];
@endphp --}}

<table {{ $attributes->merge(['class' => 'table table-hover w-[100%]']) }}>
    <thead>
        {{ $header }}
        {{-- <tr>
            @if ($activeTab !== 'custom field')
                <td>Name</td>
                <td>description</td>
                <td></td>
            @else
                <td>Name</td>
                <td>type</td>
                <td>Help Text</td>
                <td></td>
            @endif
        </tr> --}}
    </thead>
    <tbody>
        {{ $slot }}
        {{-- @foreach ($data as $key => $dataItem)
            <tr id="row-{{ $dataItem->id }}">
                <td class="w-64">{{ $dataItem->name }}</td>
                <td class="w-[50%]">
                    <span class="desc-text">{{ $dataItem->description }}</span>
                    <input type="text" class="desc-input" style="display: none;" value="{{ $dataItem->description }}">
                </td>
                <td>
                    <a class="btn btn-outline-primary edit-btn" data-row-id="{{ $dataItem->id }}">Edit</a>
                    <a class="btn btn-outline-success save-btn" data-row-id="{{ $dataItem->id }}"
                        style="display: none;">Save</a>
                    <a class="btn btn-outline-secondary cancel-btn" data-row-id="{{ $dataItem->id }}"
                        style="display: none;">Cancel</a>

                    <form action="{{ route('setting.delete', ['tab' => $activeTab, 'id' => $dataItem->id]) }}"
                        method="post" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach --}}
    </tbody>
</table>
