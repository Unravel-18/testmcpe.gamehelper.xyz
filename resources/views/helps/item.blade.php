@extends('layouts.app')

@section('title')
<title>Help</title>
@endsection

@section('title_header')
Help
@endsection

@section('page_content')
@if($item)
<div>
<h5 class="card-title">
{{ $item->name }}
</h5>
</div>
<hr/>
@else
<div>
<h5 class="card-title">
New Help
</h5>
</div>
<hr/>
@endif
<div class="card">
							<div class="card-body">
                            <div class="p-4 border rounded">
							  @if (count($errors) > 0)
                                 <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
									<div class="d-flex align-items-center">
										<div class="ms-3">
											@foreach (array_slice($errors->all(), 0, 4) as $error)
                                              <div class="text-white">{{ $error }}</div>
                                            @endforeach
										</div>
									</div>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>     
						       @endif
                                    
                                    <form class="row g-3 needs-validation" method="post" action="{{ $item ? route('helps.update', ['id' => $item->id]) : route('helps.store') }}">
										{{ csrf_field() }}
                                        
                                        <input type="hidden" name="req_edit" value="1" />
                                        @if ($item)
                                        <input type="hidden" name="id" id="id" value="{{ $item->id }}" />
                                        @endif
                                        
                                        <div class="col-12">
											<label for="validationCustom03" class="form-label">Api</label>
											<select id="validationCustom03" name="dataitem[api_id]" class="form-control" onchange="" required="">
                                              <option value=""></option>
                                              @foreach($apis as $apikey => $api)
                                              <option value="{{ $api->id }}" @if((old('req_edit') ? old('dataitem.api_id') : ($item ? $item->api_id : '')) == $api->id) selected="" @endif>{{ $api->name }}</option>
                                              @endforeach
                                            </select>
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        @foreach($languages as $languagekey => $language)
                                        <div class="col-12">
											<label for="validationCustom04{{ $languagekey }}" class="form-label">Name {{ $language->language }}</label>
											<input type="text" class="form-control" id="validationCustom04{{ $languagekey }}" name="languagedataitem[{{ $language->id }}][name]" value="{{ old('req_edit') ? old('datalanguage.'.$language->id.'.name') : ($item && $item->helpLanguagesByLanguageId($language->id) ? $item->helpLanguagesByLanguageId($language->id)->name : '') }}" />
											@if($errors->has('names' . $language->id))
                                              @foreach($errors->get('names' . $language->id) as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        <div class="col-12">
											<label for="validationCustom05{{ $languagekey }}" class="form-label">Text {{ $language->language }}</label>
											<textarea class="form-control" id="validationCustom05{{ $languagekey }}" name="languagedataitem[{{ $language->id }}][text]">{{ old('req_edit') ? old('datalanguage.'.$language->id.'.text') : ($item && $item->helpLanguagesByLanguageId($language->id) ? $item->helpLanguagesByLanguageId($language->id)->text : '') }}</textarea>
                                            @if($errors->has('names' . $language->id))
                                              @foreach($errors->get('names' . $language->id) as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        @endforeach                                        
                                        
										<div class="col-12">
											<button class="btn btn-primary" type="submit">Save</button>
										</div>
									</form>
								</div>
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