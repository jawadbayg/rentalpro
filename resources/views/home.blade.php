@extends('layouts.app')

@section('content')
<div class="admin-dashboard-page">
    <div class="container-fluid px-3 px-md-4 px-xl-5 py-4">
        @if (Auth::user()->hasRole('Admin'))
            @include('partials.admin_dashboard')
        @elseif (Auth::user()->hasRole('FP'))
            @include('partials.fp_dashboard')
        @endif
    </div>
</div>
@endsection
