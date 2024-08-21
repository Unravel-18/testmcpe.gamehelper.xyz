@extends('layouts.app')

@section('title')
<title>Language</title>
@endsection

@section('title_header')
Language
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
New Language
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
                                    
                                    <form class="row g-3 needs-validation" method="post" action="{{ $item ? route('languages.update', ['id' => $item->id]) : route('languages.store') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
                                        
                                        <input type="hidden" name="req_edit" value="1" />
                                        @if ($item)
                                        <input type="hidden" name="id" id="id" value="{{ $item->id }}" />
                                        @endif
                                        
                                        <div class="col-12">
											<label for="validationCustom01" class="form-label">Language</label>
											<input type="text" class="form-control" id="validationCustom01" name="dataitem[language]" value="{{ old('req_edit') ? old('dataitem.language') : ($item ? $item->language : '') }}" required="">
											@if($errors->has('language'))
                                              @foreach($errors->get('language') as $message)
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
											<label for="validationCustom03" class="form-label">Flag</label>
											<input name="flag_file" class="form-control" type="file" id="validationCustom03" />
                                            @if($errors->has('file'))
                                              @foreach($errors->get('file') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
                                                                                        
                                            @if($item && $item->flag)
                                            <div class="block-img-upload" data-id="{{ $item->id }}"><a target="_blank" href="/images/{{ $item->flag }}"><img src="/images/{{ $item->flag }}" style="max-width:100px;" /></a><span class="fa fa-times" onclick="deleteImgFlag(this)"></span></div>
                                            @endif
										</div>  
                                        
                                        <div class="col-12">
											<label for="validationCustom02" class="form-label">Description</label>
											<textarea id="validationCustom02" class="form-control" name="dataitem[description]">{{ old('req_edit') ? old('dataitem.description') : ($item ? $item->description : '') }}</textarea>
                                            @if($errors->has('description'))
                                              @foreach($errors->get('description') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
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
function deleteImgFlag(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить Flag?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/languages/delete_flag",
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