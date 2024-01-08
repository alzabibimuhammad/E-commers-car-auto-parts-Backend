@extends('Auth.layout')

@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Add Category</div>
                  <div class="card-body">

                    @if (session()->get('utype')==0)

                      <form action="{{ route('save.category') }}" method="get">
                          @csrf
                          <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                            <div class="col-md-6">
                                <input type="text" id="name" class="form-control" name="name" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>


                          <div class="form-group row">
                              <label for="description" class="col-md-4 col-form-label text-md-right">description</label>
                              <div class="col-md-6">
                                  <input type="text" id="description" class="form-control" name="description" required autofocus>
                                  @if ($errors->has('description'))
                                      <span class="text-danger">{{ $errors->first('description') }}</span>
                                  @endif
                              </div>
                          </div>
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  ADD
                              </button>
                          </div>
                      </form>
                      @endif

                      @if (session()->get('utype')==2)
                      <form action="{{ route('save.propose.category') }}" method="get">
                        @csrf
                        <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                          <div class="col-md-6">
                              <input type="text" id="name" class="form-control" name="name" required autofocus>
                              @if ($errors->has('name'))
                                  <span class="text-danger">{{ $errors->first('name') }}</span>
                              @endif
                          </div>
                      </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">description</label>
                            <div class="col-md-6">
                                <input type="text" id="description" class="form-control" name="description" required autofocus>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                ADD
                            </button>
                        </div>
                    </form>
                      @endif
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection
