<p class="feature-description">This page shows your organizations policies, please read them and ackowledge you have read each policy. If you have any questions regarding the course please speak to your manager.</p>

<div class="row">
    @foreach($policies as $policy)
    <div class="col col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="{{route('policy.show', $policy)}}"><div class="card card-feature-policies" style="background-color: #F4F4F5;">
            <div class="card-body">
                <img src="{{asset('assets/images/policies-icon.png')}}" class="policies-icon" />
                <h4>{{$policy->name}}</h4>
                <p>{{$policy->name_on_policy}}, {{$policy->date->format('d/m/Y')}}</p>
            </div>
        </div></a>
    </div>
    @endforeach
</div>
