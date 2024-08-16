
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Asset
        </h2>
    </x-slot>
    <div class="container">
    <x-table class="">
        <x-slot name="header">
                <th>code</th>
                <th>name</th>
                <th>category</th>
                <th>status</th>
                <th>action</th>
        </x-slot>

        <x-slot name='slot'>
            @if (!$assets->isEmpty())
                @foreach ($assets as $asst )
                        <tr>
                            <th scope="col">{{ $asst->id }}</th>
                            <td>{{ $asst->name }}</td>
                            <td>{{ $asst->category }}</td>
                            <td>{{ $asst->status }}</td>
                            <td class=" w-40">
                                <div class="grp flex justify-between">
                                    <x-primary-button class=" btn-success">Edit</x-primary-button>
                                    <x-danger-button>delete</x-danger-button>
                                </div>
                            </td>
                        </tr>
                @endforeach
            @else
                <tr class="text-center text-gray-800">
                    <td colspan='5' style="color: rgb(177, 177, 177)" >No List</td>
                </tr>
            @endif
        </x-slot>
    </x-table>
</x-app-layout>
