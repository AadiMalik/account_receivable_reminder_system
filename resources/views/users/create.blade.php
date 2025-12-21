@extends('layouts.master')
@section('title', 'Add/Edit User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
      <div>
            <h4 class="mb-1">{{isset($user)?'Edit ':'Add New '}} User</h4>
            <p class="text-muted mb-0">Fill in the details to {{isset($user)?'edit ':'add a new '}} user</p>
      </div>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
      </a>
</div>

<div class="card">
      <div class="card-body">
            <form action="{{ isset($user)?route('users.update', $user->id):route('users.store') }}" method="POST">
                  @csrf
                  @if(isset($user))
                  @method('PUT') <!-- Add this line for update -->
                  @endif
                  <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ isset($user)?$user->name:old('name') }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>

                  <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ isset($user)?$user->email:old('email') }}">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>

                  <div class="mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ isset($user)?$user->phone:old('phone') }}">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>

                  <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
                  <div class="mb-3">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>

                  <button class="btn btn-blue">{{isset($user)?'Update ':'Create '}} User</button>
            </form>
      </div>
</div>
@endsection