@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $title ?? 'Coming Soon' }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">{{ $title ?? 'Module' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body text-center p-5">
                <div class="mt-4">
                    <h4 class="mb-3">{{ $title ?? 'Feature Under Development' }}</h4>
                    <p class="text-muted mb-4">{{ $description ?? 'This module is currently being built. Please check back later.' }}</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
