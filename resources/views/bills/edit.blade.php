@extends('main')

@section('content')

<div class="container">
      <div class="starter-template">
        <h1>Uredi trošak</h1>
          {!! var_dump($errors->all()) !!}
          {!! Form::model($bill, array('route' => array('bills.update', $bill->id), 'method' => 'PUT', 'class' => 'form-horizontal')) !!}
              <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Korisnik</label>
                      <div class="col-sm-10">
                          {!! Form::select('team', ['GL' => 'Gluhaki','GE' => 'Gerenčiri'], null, ['class' => 'form-control']) !!}
                      </div>
              </div>
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Iznos</label>
                  <div class="col-sm-10">
                    <div class="row">
                        <div class="col-xs-10">
                          {!! Form::text('price', Input::old('price'), array('class' => 'form-control')) !!}
                        </div>
                        <div class="col-xs-2">
                          {!! Form::select('currency', ['eur' => '€','kn' => 'kn'], null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                  </div>
              </div>
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Opis</label>
                <div class="col-sm-10">
                  {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                </div>
              </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">

                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group btn-group-lg" role="group">
                      {!! Form::submit('Spremi', array('class' => 'btn btn-success')) !!}
                    </div>
                    <div class="btn-group btn-group-lg" role="group">
                      {!! Form::hidden('_method', 'DELETE') !!}
                      {!! Form::hidden('onsubmit', "return confirm('Jeste li sigurni da želite izbrisati ovaj trošak?')") !!}
                      {!! Form::submit('Obriši', array('class' => 'btn btn-danger')) !!}
                    </div>
                  </div>
                  </div>
                </div>
          {!! Form::close() !!}
  </div> <!-- starter-template -->
</div>

@endsection
