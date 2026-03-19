@extends('layouts.app')

@section('content')
    {{-- Phase 13: Testing Checklist --}}
    <div class="container-xxl bg-white p-0">
        <div class="card shadow-lg m-4">
            <div class="card-header">
                <h3>Testing Checklist</h3>
            </div>

            <div class="card-body">
                {{-- City navigation --}}
                <h5 class="mb-3">1. City Navigation</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_city_1">
                    <label class="form-check-label" for="t_city_1">Home page loads city list successfully.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_city_2">
                    <label class="form-check-label" for="t_city_2">City page shows correct hotels for that city.</label>
                </div>

                {{-- Hotel page --}}
                <h5 class="mt-4 mb-3">2. Hotel Page</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_hotel_1">
                    <label class="form-check-label" for="t_hotel_1">Hotel detail page shows available rooms only.</label>
                </div>

                {{-- Room reservation --}}
                <h5 class="mt-4 mb-3">3. Room Reservation</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_res_1">
                    <label class="form-check-label" for="t_res_1">Reserve button opens reservation form with selected room (?room=ID).</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_res_2">
                    <label class="form-check-label" for="t_res_2">Invalid dates/guests validation errors appear correctly.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_res_3">
                    <label class="form-check-label" for="t_res_3">Double booking is blocked with: “This room is not available for the selected dates.”</label>
                </div>

                {{-- Login system --}}
                <h5 class="mt-4 mb-3">4. Login System</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_login_1">
                    <label class="form-check-label" for="t_login_1">Guest clicks Reserve -> redirect to login page.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_login_2">
                    <label class="form-check-label" for="t_login_2">After login, user is returned to /hotels/{hotel}/reserve.</label>
                </div>

                {{-- Admin dashboard --}}
                <h5 class="mt-4 mb-3">5. Admin Dashboard</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_Admin_1">
                    <label class="form-check-label" for="t_Admin_1">Admin can view dashboard stats for their hotel.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_Admin_2">
                    <label class="form-check-label" for="t_Admin_2">Admin can view rooms list and manage rooms.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_Admin_3">
                    <label class="form-check-label" for="t_Admin_3">Admin can view reservations for their hotel only.</label>
                </div>

                {{-- Super Admin dashboard --}}
                <h5 class="mt-4 mb-3">6. Super Admin Dashboard</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_super_1">
                    <label class="form-check-label" for="t_super_1">Super Admin can see cities/hotels overview and reservation counts.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_super_2">
                    <label class="form-check-label" for="t_super_2">Super Admin can create/edit cities and hotels.</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="t_super_3">
                    <label class="form-check-label" for="t_super_3">Super Admin can assign/remove hotel Admins from hotels.</label>
                </div>
            </div>
        </div>
    </div>
@endsection
