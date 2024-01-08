@extends('Auth.layout')
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Add part</div>
                  <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                      <form action="{{ route('save.part') }}" >
                          @csrf
                          <div class="form-group row">
                              <label for="name" class="col-md-4 col-form-label text-md-right">name</label>
                              <div class="col-md-6">
                                  <input type="text"  class="form-control" name="name" required autofocus>
                                  @if ($errors->has('name'))
                                      <span class="text-danger">{{ $errors->first('name') }}</span>
                                  @endif
                              </div>
                          </div>
                          <input type="hidden" id="seller_id" name="seller_id" value={{session()->get('id')}} >
                          <div class="form-group row">
                            <label for="car_id" class="col-md-4 col-form-label text-md-right">Car ID</label>
                            <div class="col-md-6">
                                <input type="number" id="car_id" class="form-control" name="car_id" required>
                                @if ($errors->has('car_id'))
                                    <span class="text-danger">{{ $errors->first('car_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category_id" class="col-md-4 col-form-label text-md-right">Categories</label>
                            <div class="col-md-6">
                                <select name="category_id" >
                                    @foreach ($categories as $category)
                                    <option value={{$category->id}}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">Amount</label>
                            <div class="col-md-6">
                                <input type="number" id="amount" class="form-control" name="amount" required>
                                @if ($errors->has('amount'))
                                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                                @endif
                            </div>
                        </div>
                          <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-right">price</label>
                            <div class="col-md-6">
                                <input type="number" id="price" class="form-control" name="price" required>
                                @if ($errors->has('price'))
                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                            <div class="col-md-6">
                                <input type="text" id="description" class="form-control" name="description" required>
                                @if ($errors->has('amount'))
                                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                                @endif
                            </div>
                        </div>
                          <div class="col-md-6 offset-md-4">
                              <button type="submit" class="btn btn-primary">
                                  submit
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection
