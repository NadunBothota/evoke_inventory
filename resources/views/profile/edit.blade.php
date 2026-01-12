@extends('layouts.app')

@section('content')
    <h2 class="h4 font-weight-bold">
        {{ __('Profile') }}
    </h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card my-4">
                <div class="card-header">
                    {{ __('Update Profile Information') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card my-4">
                <div class="card-header">
                    {{ __('Update Password') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card my-4">
                <div class="card-header">
                    {{ __('Delete Account') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
