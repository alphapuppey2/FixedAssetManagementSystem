<table>
    <thead>
        <th>Name</th>
        <th>description</th>
        <th>action</th>
    </thead>
    <tbody>
        @foreach ($model as $data)
            <tr>
                <td>{{ $data->name }}</td>
                <td>{{ $data->description }}</td>
                <td>
                    <a class="btn btn-outline-primary">Edit</a>
                    <a class="btn btn-outline-danger">Delete</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
