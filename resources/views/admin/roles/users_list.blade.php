@if($users->count())
    <div class="table-responsive">
        <table class="table datatable">
            <thead class="thead-light">
            <tr>
                <th>User</th>
                <th>Email</th>
                <th class="text-center">Phone</th>
                <th class="text-center">Active</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td class="text-center">
                        @if($row->is_active)
                        <span class="p-1 pe-2 rounded-1 text-primary bg-success-transparent fs-10">
                            Yes
                        </span>
                        @else
                        <span class="p-1 pe-2 rounded-1 text-danger bg-danger-transparent fs-10">
                            No
                        </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-danger mb-0">No users attached to this role.</div>
@endif
