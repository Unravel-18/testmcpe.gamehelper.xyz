@extends('layouts.app')

@section('title')
<title>Category</title>
@endsection

@section('title_header')
Category
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
New Category
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
                                    
                                    <form class="row g-3 needs-validation" method="post" action="{{ $item ? route('categories.update', ['id' => $item->id]) : route('categories.store') }}" enctype="multipart/form-data">
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
                                              <option value="{{ $api->id }}" @if(old('req_edit') ? old('dataitem.api_id') : ($item ? $item->api_id : '') == $api->id) selected="" @endif>{{ $api->name }}</option>
                                              @endforeach
                                            </select>
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>
                                        
                                        <div class="col-12">
											<label for="validationCustom01" class="form-label">Shortcode</label>
											<input type="text" class="form-control" id="validationCustom01" name="dataitem[shortcode]" value="{{ old('req_edit') ? old('dataitem.shortcode') : ($item ? $item->shortcode : '') }}" required="">
											@if($errors->has('shortcode'))
                                              @foreach($errors->get('shortcode') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div>  
                                        
                                        <div class="col-12">
											<label for="validationCustom03" class="form-label">Иконка</label>
											<input name="icon" class="form-control" type="file" id="validationCustom03" />
                                            @if($errors->has('icon'))
                                              @foreach($errors->get('icon') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
                                                                                        
                                            @if($item && $item->icon)
                                            <div class="block-img-upload" data-id="{{ $item->id }}"><a target="_blank" href="/images/category/{{ $item->icon }}"><img src="/images/category/{{ $item->icon }}" style="max-width:100px;" /></a><span class="fa fa-times" onclick="deleteCategoryIcon(this)"></span></div>
                                            @endif
										</div>   
                                        
                                        @foreach($languages as $languagekey => $language)
                                        <div class="col-12">
											<label for="validationCustom04{{ $languagekey }}" class="form-label">Name_{{ $language->shortcode }}</label>
											<input type="text" class="form-control" id="validationCustom04{{ $languagekey }}" name="names[{{ $language->id }}]" value="{{ old('req_edit') ? old('names.'.$language->id) : ($item && $item->categoryLanguagesByLanguageId($language->id) ? $item->categoryLanguagesByLanguageId($language->id)->name : '') }}" required="">
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
function deleteCategoryIcon(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/categories/delete_icon",
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