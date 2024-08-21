@extends('layouts.app')

@section('title')
<title>Settings</title>
@endsection

@section('title_header')
Settings
@endsection

@section('page_content')
<div class="card">
							<div class="card-body">
                                <form class="form-horizontal" method="post">
                              <div class="row">
        {{ csrf_field() }}
        
          <div class="col-12 col-sm-2">
            <label for="inpt_0" class="control-label" style="font-weight: bold;">Доступ по App-Id:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input type="checkbox" @if(\App\Helpers\Setting::value('app_api_auth:'))checked=""@endif class="form-check-input" name="app_api_auth" style="width: 22px; height: 22px;" />
          </div>
          
          <div class="col-12">&nbsp;</div>

          <div class="col-12 col-sm-2">
            <label for="inpt_1" class="control-label" style="font-weight: bold;">Header(app-id):</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_1" type="text" class="form-control" name="app_id" value="{{ \App\Helpers\Setting::value('app_id:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
  
          <div class="col-12 col-sm-2">
            <label for="inpt_2" class="control-label" style="font-weight: bold;">List update:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_2" type="text" class="form-control" name="list_update" value="{{ \App\Helpers\Setting::value('list_update:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
  
          <div class="col-12 col-sm-2">
            <label for="inpt_3" class="control-label" style="font-weight: bold;">Ads day:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_3" type="text" class="form-control" name="ads_d" value="{{ \App\Helpers\Setting::value('ads_d:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
  
          <div class="col-12 col-sm-2">
            <label for="inpt_4" class="control-label" style="font-weight: bold;">Ads click:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_4" type="text" class="form-control" name="adspro_c" value="{{ \App\Helpers\Setting::value('adspro_c:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
  
          <div class="col-12 col-sm-2">
            <label for="inpt_5" class="control-label" style="font-weight: bold;">Pro *click:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_5" type="text" class="form-control" name="pro_c" value="{{ \App\Helpers\Setting::value('pro_c:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
  
          <div class="col-12 col-sm-2">
            <label for="inpt_6" class="control-label" style="font-weight: bold;">Pro on start, day:</label>
          </div>
          <div class="col-12 col-sm-9">
            <input id="inpt_6" type="text" class="form-control" name="pro_sd" value="{{ \App\Helpers\Setting::value('pro_sd:') }}" />
          </div>
          
          <div class="col-12">&nbsp;</div>
    
          <div class="col-12">
            <button class="btn btn-primary" type="submit">Save</button>
          </div>
                              </div>
      </form>
                            </div>
</div>
@endsection

@push('styles')
<style>
</style>
@endpush

@push('scripts')
<script>
</script>
@endpush
