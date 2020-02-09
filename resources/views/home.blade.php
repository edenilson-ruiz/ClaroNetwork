@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <div class="row">
                            <div class="col">
                                <div class="card" style="width: 12rem;">
                                  <img src="{{ url('img/2G.png') }}" class="card-img-top" alt="...">
                                  <div class="card-body">
                                    <h5 class="card-title">Red EDGE (2G)</h5>
                                    <a href="/network2g" class="btn btn-primary">Ver estado</a>
                                  </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card" style="width: 12rem;">
                                  <img src="{{ url('img/red.png') }}" class="card-img-top" alt="...">
                                  <div class="card-body">
                                    <h5 class="card-title">Red HSDPA (3G)</h5>
                                    <a href="/network3g" class="btn btn-primary">Ver estado</a>
                                  </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card" style="width: 12rem;">
                                  <img src="{{ url('img/lte.png') }}" class="card-img-top" alt="..." width="200px" height="200px">
                                  <div class="card-body">
                                    <h5 class="card-title">Red LTE (4G)</h5>
                                    <a href="/networkLte" class="btn btn-primary">Ver estado</a>
                                  </div>
                                </div>
                            </div>
                             <div class="col">
                                <div class="card" style="width: 12rem;">
                                  <img src="{{ url('img/wifi.png') }}" class="card-img-top" alt="...">
                                  <div class="card-body">
                                    <h5 class="card-title">Red WiFi</h5>
                                    <a href="/networkWifi" class="btn btn-primary">Ver estado</a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
