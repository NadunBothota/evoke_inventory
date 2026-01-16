@extends('layouts.app')

@section('header_title', 'Profile')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">{{ __('Profile Information') }}</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">{{ __('Update Password') }}</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Delete Account') }}</div>
                <div class="card-body">
                     @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection