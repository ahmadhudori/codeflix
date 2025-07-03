@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 100px;">
    <div class="row">
        <div class="col-md-3">
            <!-- Content -->
            <ul class="list-group">
                <li class="list-group-item">
                    <a class="group-profile {{ request()->is('profile/overview') ? 'active' : '' }}"
                        href="{{ route('profile.overview') }}"><i class="fa-solid fa-circle-user"></i> Overview</a>
                </li>
            </ul>
        </div>
        <div class="col-md-9">
            <!-- Content -->
            <section class="d-block">
                <div class=" membership" id="membership">
                    <h5 class="membership-title">Overview</h5>
                    Plan Detail
                    <div class="p-3 mt-2 mb-4 box">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="overview-plan">{{ $membership->plan->title }}</h2>
                                <h6 class="w-auto overview-payment-date">{{ $membership->plan->resolution}} video
                                    resolution, ad-free watching
                                    and
                                    more</h6>
                            </div>
                        </div>
                        <hr class="line-overview">
                        <span class="btn-manage-membership text-md-end" href="#" id="manage-membership">
                            <p class="manage-membership">Next Payment: {{ $membership->end_date->format('d M Y') }}</p>
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection