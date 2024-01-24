@extends('layout.app')
@section('content')
<div class="container mt-5">
    <div class="row mt-5">
      <div class="col-md-8 offset-md-2 mt-5">
        <h1 class="text-center mt-5">
          Register
        </h1>
          <div class="card">
            <div class="card-body">
                <form action="{{url('/register')}}" method="post" id="register_form">
                  @csrf
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" required value="{{old('name')}}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label for="email">Email address*</label>
                      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{old('email')}}">
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile no.*</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    {{config('constants.country_code')}}
                                </span>
                            </div>
                            <input type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile" required name="mobile" value="{{old('mobile')}}">
                        </div>
                      <span class="form-text text-muted">
                        Enter mobile number without country code. ex: 98XXXXXXXX
                      </span>
                      @error('mobile')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="password">Password*</label>
                      <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required >
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="form-group">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-lg">
                                <input type="checkbox" name="disclaimer" id="disclaimer" required>
                                <span></span>
                                <p>
                                    <b>I</b> certify that the information provided in this application is correct to the best of my knowledge. I understand that to falsify information is grounds for rejecting my application. <b>I</b> authorize representatives/volunteers of Maatram Foundation to validate and verify any/all information provided in the application form through voice or video calls/house visit etc.
                                </p>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        Register
                    </button>
                </form>
                <div class="row text-center mt-5">
                    <div class="col-md-12">
                        Already have an account?
                        <strong>
                            <a href="{{ route('login') }}" class="text-secondary">Login</a>
                        </strong>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

