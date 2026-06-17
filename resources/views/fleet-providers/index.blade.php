@extends('layouts.app')

@section('content')
<div class="container mt-2 users_index_container">
    <div class="row">
        <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="mb-0">Fleet Providers</h2>
            <a class="btn-black" href="{{ route('fleet-providers.create') }}"><i class="fa fa-plus"></i> Create Fleet Provider</a>
        </div>
    </div>

    @session('success')
        <div class="alert alert-success mt-3" role="alert">
            {{ $value }}
        </div>
    @endsession

    <table id="fleetProvidersTable" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Fleet Count</th>
                <th width="220px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fleetProviders as $key => $provider)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $provider->name }}</td>
                    <td>{{ $provider->email }}</td>
                    <td>{{ $provider->fpDetail->address ?? '—' }}</td>
                    <td>{{ $provider->fleet_count }}</td>
                    <td>
                        <a class="btn-black-sm" href="{{ route('fleet-providers.edit', $provider->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('fleet-providers.destroy', $provider->id) }}" class="d-inline" onsubmit="return confirm('Delete this fleet provider? Their fleet records will also be removed.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-black-sm">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No fleet providers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#fleetProvidersTable').DataTable();
    });
</script>
@endsection
