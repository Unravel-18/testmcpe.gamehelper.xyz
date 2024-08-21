@extends('layouts.app')

@section('title')
<title>
@if($item)
{{ $item->skinid }}
@endif
</title>
@endsection

@section('title_header')
<span style="white-space: nowrap;">
@if($item)
{{ $item->skinid }}
@else
New
@endif
</span>
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
New
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
                                    
                                    <form class="row g-3 needs-validation" method="post" action="{{ $item ? route('skins.update', ['id' => $item->id]) : route('skins.store') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
                                        
                                        <input type="hidden" name="req_edit" value="1" />
                                        @if ($item)
                                        <input type="hidden" name="id" id="id" value="{{ $item->id }}" />
                                        @endif
                                        
                                        @if ($itemapi)
                                        <input type="hidden" name="api_id" id="api_id" value="{{ $itemapi->id }}" />
                                        @endif
                                        
                                        <div class="col-12">
											<button class="btn btn-primary" type="submit">Save</button>
                                            @if ($itemapi)
                                            <a href="{{ $urlback }}" class="btn btn-info">List {{ $itemapi->name }}</a>
                                            @else
                                            <a href="{{ $urlback }}" class="btn btn-info">All content</a>
                                            @endif
										</div>    
                                        
                                        <div class="col-12">
											<label for="dataitem-api_id" class="form-label">Api</label>
											<select id="dataitem-api_id" name="dataitem[api_id]" class="form-control" onchange="changeApiId()" required="">
                                              <option value=""></option>
                                              @foreach($apis as $apikey => $api)
                                              <option value="{{ $api->id }}" @if(old('req_edit') ? old('dataitem.api_id') : ($item ? $item->api_id : ($itemapi ? $itemapi->id : '')) == $api->id) selected="" @endif>{{ $api->name }}</option>
                                              @endforeach
                                            </select>
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        <div class="col-12">
											<label for="validationCustom05" class="form-label">Language</label>
											<select onchange="changeLanguage(this)" id="validationCustom05" name="" class="form-control" onchange="" required="">
                                              @foreach($languages as $languagekey => $language)
                                              <option value="{{ $language->id }}" @if((old('req_edit') ? old('dataitem.language_id') : ($item ? $item->language_id : '')) == $language->id) selected="" @endif>{{ $language->language }}</option>
                                              @endforeach
                                            </select>
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>  
                                        
                                        @foreach($languages as $languagekey => $language)
                                        <div class="col-6 lng_block lng_block_{{ $language->id }}" style="@if((old('req_edit') ? old('dataitem.language_id') : ($item ? $item->language_id : '')) == $language->id || (!((old('req_edit') ? old('dataitem.language_id') : ($item ? $item->language_id : ''))) && $languagekey == 0)) @else display: none; @endif">
											<label for="validationCustom01{{ $language->id }}" class="form-label">Name_{{ $language->shortcode }}</label>
											<input type="text" class="form-control" id="validationCustom01{{ $language->id }}" name="languagedataitem[{{ $language->id }}][name]" value="{{ old('req_edit') ? old('languagedataitem.'.$language->id.'.name') : ($item && $item->skinLanguagesByLanguageId($language->id) ? $item->skinLanguagesByLanguageId($language->id)->name : '') }}">
											@if($errors->has('name'))
                                              @foreach($errors->get('name') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div> 
                                        <div class="col-6 lng_block lng_block_{{ $language->id }}" style="@if((old('req_edit') ? old('dataitem.language_id') : ($item ? $item->language_id : '')) == $language->id || (!((old('req_edit') ? old('dataitem.language_id') : ($item ? $item->language_id : ''))) && $languagekey == 0)) @else display: none; @endif">
											<label for="validationCustom07{{ $language->id }}" class="form-label">Description_{{ $language->shortcode }}</label>
											<textarea id="validationCustom07{{ $language->id }}" class="form-control" name="languagedataitem[{{ $language->id }}][description]">{{ old('req_edit') ? old('languagedataitem.'.$language->id.'.description') : ($item && $item->skinLanguagesByLanguageId($language->id) ? $item->skinLanguagesByLanguageId($language->id)->description : '') }}</textarea>
                                            @if($errors->has('description'))
                                              @foreach($errors->get('description') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        @endforeach     
                                        
                                        <div class="col-12">
											<label for="validationCustom08" class="form-label">SkinId</label>
											<input type="text" class="form-control" id="validationCustom08" name="dataitem[skinid]" value="{{ old('req_edit') ? old('dataitem.skinid') : ($item ? $item->skinid : '') }}">
											@if($errors->has('skinid'))
                                              @foreach($errors->get('skinid') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>  
                                        
                                        <div class="col-12">
											<label for="dataitem-category_id" class="form-label">Category</label>
											<select id="dataitem-category_id" name="dataitem[category_id]" class="form-control" onchange="" required="">
                                              <option value=""></option>
                                              @foreach($categories as $categorykey => $category)
                                              <option value="{{ $category->id }}" @if((old('req_edit') ? old('dataitem.category_id') : ($item ? $item->category_id : '')) == $category->id) selected="" @endif>{{ $category->name }}</option>
                                              @endforeach
                                            </select>
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        <div class="col-12">
											<label for="validationCustom08" class="form-label">Min version</label>
											<input type="text" class="form-control" id="validationCustom08" name="dataitem[min_version]" value="{{ old('req_edit') ? old('dataitem.min_version') : ($item ? $item->min_version : '') }}">
											@if($errors->has('min_version'))
                                              @foreach($errors->get('min_version') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>  
                                        
                                        <div class="col-12">
											<label for="validationCustom09" class="form-label">Price</label>
											<input type="number" class="form-control" id="validationCustom09" name="dataitem[price]" value="{{ old('req_edit') ? old('dataitem.price') : ($item ? $item->price : '') }}">
											@if($errors->has('price'))
                                              @foreach($errors->get('price') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div> 
                                        
                                        <div class="col-12">
											<label for="validationCustomimages" class="form-label">Img</label>
											<input name="images[]" class="form-control" type="file" id="validationCustomimages" multiple="" />
                                            @if($errors->has('images'))
                                              @foreach($errors->get('images') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
                                            
                                            @if($item)                                            
                                            @foreach($item->getImages() as $image)
                                            <span style="margin-right: 22px;" class="block-img-upload" data-id="{{ $item->id }}" data-image="{{ $image }}">
                                              <a target="_blank" href="{{ $item->assetImage($image) }}">
                                                <img src="{{ $item->assetImageSmall($image) }}" style="max-width:100px;" />
                                              </a>
                                              <span class="fa fa-times" onclick="deleteSkinImg(this)"></span>
                                            </span>
                                            @endforeach
                                            @endif
										</div> 
                                        
                                        <div class="col-12">
											<label for="validationCustom12" class="form-label">Downloads</label>
											<input type="number" class="form-control" id="validationCustom12" name="dataitem[downloads]" value="{{ old('req_edit') ? old('dataitem.downloads') : ($item ? $item->downloads : 0) }}">
											@if($errors->has('downloads'))
                                              @foreach($errors->get('downloads') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        <div class="col-12">
											<label for="validationCustom10" class="form-label">Likes</label>
											<input type="number" class="form-control" id="validationCustom10" name="dataitem[likes]" value="{{ old('req_edit') ? old('dataitem.likes') : ($item ? $item->likes : 0) }}">
											@if($errors->has('likes'))
                                              @foreach($errors->get('likes') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div> 
                                        
                                        <div class="col-12">
											<label for="validationCustom11" class="form-label">Views</label>
											<input type="number" class="form-control" id="validationCustom11" name="dataitem[views]" value="{{ old('req_edit') ? old('dataitem.views') : ($item ? $item->views : 0) }}">
											@if($errors->has('views'))
                                              @foreach($errors->get('views') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        <div class="col-12">
											<label for="validationCustom03" class="form-label">File</label>
											
                                            <div class="input-group mb-3">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">URL</span>
                                              </div>
                                              <input type="text" class="form-control" id="" name="dataitem[file_link]" value="{{ old('req_edit') ? old('dataitem.file_link') : ($item ? $item->file_link : '') }}">
                                              @if($item && $item->file_link)
                                              <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">{{ $item->getSizeFileLink() }}</span>
                                              </div>
                                              @endif
                                            </div>

											<input name="file" class="form-control" type="file" id="validationCustom03" />
                                            @if($errors->has('file'))
                                              @foreach($errors->get('file') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
                                            @if($item && $item->file)
                                            <div class="block-img-upload" data-id="{{ $item->id }}"><a target="_blank" href="/adminfiles/{{ $item->api->shortcode }}/{{ $item->file }}">
                                            @if(stripos($item->file, 'jpeg') || stripos($item->file, 'jpg') || stripos($item->file, 'gif') || stripos($item->file, 'png'))
                                            <img src="/adminfiles/{{ $item->api->shortcode }}/{{ $item->file }}" style="max-width:100px;" />
                                            @else
                                            <img width="64" src="/img/2245332.png" />
                                            @endif
                                            </a><span class="fa fa-times" onclick="deleteSkinFile(this)"></span>
                                            <div>
                                            <span>{{ $item->getfilesize() }}</span>
                                            </div>
                                            </div>
                                            @endif
										</div> 
                                        
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
function changeApiId() {
    $("#dataitem-category_id").empty();
    
    $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/categories/get_json",
		    	data: {
		    	   api_id: $("#dataitem-api_id").val()
                },
                type: 'POST',
                dataType: 'json',
		    	success: function(result) {
			        if(result.status == 1) {
			            for (var i in result.categories) {
			                $("#dataitem-category_id").append('<option value="'+result.categories[i].id+'">'+result.categories[i].name+'</option>');
			            }
			        } 
		    	},
			    error: function (result) {
			     
			    }
   	});
}

function deleteImgSound(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить Sound?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/delete_sound",
		    	data: {
		    	   id: obj_block.attr('data-id')
                },
                type: 'POST',
                dataType: 'json',
		    	success: function(result) {
			        if(result.status == 1){
			            obj_block.remove();
			        } 
		    	},
			    error: function (result) {
			    }
	    	});
      }
    }
}
function deleteImgScreenshot(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить Screenshot?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/delete_screenshot",
		    	data: {
		    	   id: obj_block.attr('data-id')
                },
                type: 'POST',
                dataType: 'json',
		    	success: function(result) {
			        if(result.status == 1){
			            obj_block.remove();
			        } 
		    	},
			    error: function (result) {
			    }
	    	});
      }
    }
}

function changeLanguage(obj) {
    $(".lng_block").hide();
    $(".lng_block_" + $(obj).val()).show();
}

function deleteSkinImg(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/delete_img",
		    	data: {
		    	   id: obj_block.attr('data-id'),
		    	   image: obj_block.attr('data-image')
                },
                type: 'POST',
                dataType: 'json',
		    	success: function(result) {
			        if(result.status == 1){
			            obj_block.remove();
			        } 
		    	},
			    error: function (result) {
			    }
	    	});
      }
    }
}

function deleteSkinFile(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/delete_file",
		    	data: {
		    	   id: obj_block.attr('data-id')
                },
                type: 'POST',
                dataType: 'json',
		    	success: function(result) {
			        if(result.status == 1){
			            obj_block.remove();
			        } 
		    	},
			    error: function (result) {
			    }
	    	});
      }
    }
}
</script>
@endpush