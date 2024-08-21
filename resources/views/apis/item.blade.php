@extends('layouts.app')

@section('title')
<title>Api</title>
@endsection

@section('title_header')
Api
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
New Api
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
                                    
                                    <form class="row g-3 needs-validation" method="post" action="{{ $item ? route('apis.update', ['id' => $item->id]) : route('apis.store') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
                                        
                                        <input type="hidden" name="req_edit" value="1" />
                                        @if ($item)
                                        <input type="hidden" name="id" id="id" value="{{ $item->id }}" />
                                        @endif
                                        
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
											<label for="validationCustom02" class="form-label">Name</label>
											<input type="text" class="form-control" id="validationCustom02" name="dataitem[name]" value="{{ old('req_edit') ? old('dataitem.name') : ($item ? $item->name : '') }}" required="">
											@if($errors->has('name'))
                                              @foreach($errors->get('name') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
										</div> 
                                        
                                        <div class="col-12">
											<label for="validationCustom03" class="form-label">Изображение</label>
											<input name="img" class="form-control" type="file" id="validationCustom03" />
                                            @if($errors->has('img'))
                                              @foreach($errors->get('img') as $message)
                                                <div class="text-error">{{ $message }}</div>
                                              @endforeach
                                            @endif
                                                                                        
                                            @if($item && $item->img)
                                            <div class="block-img-upload" data-id="{{ $item->id }}"><a target="_blank" href="/images/api/{{ $item->img }}"><img src="/images/api/{{ $item->img }}" style="max-width:100px;" /></a><span class="fa fa-times" onclick="deleteApiImg(this)"></span></div>
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
function deleteApiImg(obj){
    var obj_block = $(obj).parent();
    
    if(confirm('Вы действительно хотите удалить?')) {
        if(obj_block.attr('data-id')) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/apis/delete_img",
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