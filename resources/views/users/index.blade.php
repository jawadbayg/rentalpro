@extends('layouts.app')

@section('content')
    <div class="container mt-2 users_index_container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Users Management</h2>
                </div>
                <div class="pull-right">
                    <a class="btn-black mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
                </div>
            </div>
        </div>

        @session('success')
            <div class="alert alert-success" role="alert"> 
                {{ $value }}
            </div>
        @endsession

        <table id="usersTable" class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th width="280px">Action</th>
            </tr>
            @foreach ($data as $key => $user)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                        <label class="badge bg-success">{{ $v }}</label>
                        @endforeach
                    @endif
                    </td>
                    <td>
                        <a class="btn-black-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        @if(!$user->hasRole('Admin'))
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-black-sm">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif

                    </td>
                </tr>
            @endforeach
        </table>
    </div>


   
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable();
        });
    </script>


@endsection
