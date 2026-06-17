@extends('layouts.app')

@section('content')
<div class="fleet-index-page">
    <div class="fleet-index-page__header">
        <div>
            <p class="fleet-index-page__eyebrow">Fleet management</p>
            <h2 class="fleet-index-page__title">Manage Fleet</h2>
        </div>
        <a href="{{ route('fleet.create') }}" class="btn-blue">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    @session('success')
        <div class="alert alert-success fleet-index-page__alert" role="alert">
            {{ $value }}
        </div>
    @endsession

    <div class="fleet-table-card">
        <div class="fleet-table-wrap">
            <table id="fleetTable" class="table fleet-table w-100">
                <thead>
                    <tr>
                        @if ($isAdmin)
                            <th>Provider</th>
                        @endif
                        <th>Vehicle</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Plate</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th class="fleet-table__actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fleets as $fleet)
                        <tr>
                            @if ($isAdmin)
                                <td data-label="Provider">{{ $fleet->user->name ?? '—' }}</td>
                            @endif
                            <td data-label="Vehicle">{{ $fleet->vehicle_name }}</td>
                            <td data-label="Owner">{{ $fleet->vehicle_owner_name }}</td>
                            <td data-label="Type">{{ $fleet->vehicle_type }}</td>
                            <td data-label="Plate">{{ $fleet->license_plate }}</td>
                            <td data-label="Year">{{ $fleet->manufacturing_year }}</td>
                            <td data-label="Status">
                                @if ($fleet->status === 'active')
                                    <span class="fleet-status-badge fleet-status-badge--active">Active</span>
                                @elseif ($fleet->status === 'inactive')
                                    <span class="fleet-status-badge fleet-status-badge--inactive">Inactive</span>
                                @elseif ($fleet->status === 'under_maintenance')
                                    <span class="fleet-status-badge fleet-status-badge--maintenance">Maintenance</span>
                                @else
                                    <span class="fleet-status-badge">{{ ucfirst($fleet->status) }}</span>
                                @endif
                            </td>
                            <td data-label="Actions">
                                <div class="fleet-table__actions">
                                    <a href="{{ route('fleet.edit', $fleet->id) }}" class="btn-black-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <button
                                        type="button"
                                        class="btn-black-sm fleet-delete-btn"
                                        data-id="{{ $fleet->id }}"
                                        data-name="{{ $fleet->vehicle_name }}"
                                        data-token="{{ csrf_token() }}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-4 text-muted">
                                No vehicles found. Add your first vehicle to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        if ($('#fleetTable tbody tr td[colspan]').length) {
            return;
        }

        $('#fleetTable').DataTable({
            responsive: true,
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            order: [],
            language: {
                search: 'Search vehicles:',
                lengthMenu: 'Show _MENU_ vehicles',
                info: 'Showing _START_ to _END_ of _TOTAL_ vehicles',
                infoEmpty: 'No vehicles available',
                zeroRecords: 'No matching vehicles found',
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.fleet-delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const fleetId = this.dataset.id;
                const fleetName = this.dataset.name;
                const token = this.dataset.token;

                Swal.fire({
                    title: 'Delete vehicle?',
                    text: `"${fleetName}" will be permanently removed.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#01232e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then(function (result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    fetch(`/fleet/delete/${fleetId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    })
                    .then(function (response) {
                        return response.json().then(function (data) {
                            if (!response.ok) {
                                throw new Error(data.message || 'Request failed.');
                            }
                            return data;
                        });
                    })
                    .then(function (data) {
                        Swal.fire('Deleted!', data.message, 'success').then(function () {
                            location.reload();
                        });
                    })
                    .catch(function (error) {
                        Swal.fire('Error!', error.message || 'Request failed.', 'error');
                    });
                });
            });
        });
    });
</script>
@endsection
