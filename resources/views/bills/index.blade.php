@extends('main')

@section('content')

    <div class="container">

      <div class="starter-template">
        <h1>Ukupni trošak : <span class="label label-default">{{$sums['ALL']}}€</span></h1>
        </br>
        <div class="row">
        <div class="col-xs-6">
            <h3>
                <b>Medvedeci</b>
            </h3>
            <div class="well well-lg">
                <h3>
                    Ukupno : <b>{{$sums['GL']}}€</b><br>
                    {{$sums['GL_NAME']}} : <span class="label label-{{$sums['GL_LABEL']}}">{{$sums['GL_DIF']}}€</span>
                </h3>
            </div>
        </div>
        <div class="col-xs-6">
            <h3>
                <b>Gerenčiri</b>
            </h3>
            <div class="well well-lg">
                <h3>
                    Ukupno : <b>{{$sums['GE']}}€</b><br>
                    {{$sums['GE_NAME']}} : <span class="label label-{{$sums['GE_LABEL']}}">{{$sums['GE_DIF']}}€</span>
                </h3>
            </div>
        </div>

      </div>


    </div><!-- /.container -->

<!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif


    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Svi troškovi</h3>
      </div>
      <div class="panel-body">

        <div class="list-group">
        @foreach($bills as $key => $value)
            <button onclick="window.location.href='{{ URL::to('bills/' . $value->id . '/edit') }}'"
                    type="button"
                        class="list-group-item">
                            {{ $value->description }} - <b>{{ $teams[$value->team] }}</b> - <span class="label label-default">{{ $value->price }}{{ $value->currency }}</span></button>
        @endforeach
        </div>

      </div>
    </div>
@endsection
