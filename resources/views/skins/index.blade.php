@extends('layouts.app')

@section('title')
<title>All content</title>
@endsection

@push('styles')
<style>
.chosen-choices{
    min-height: 38px!important;
    width: 170px!important;
    border-radius: 0.25rem!important;
}
.chosen-search-input {
    margin: 5px 0px 5px 5px!important;
}
.find_field[type=text], select.find_field {
    width: 100%;
    padding: 1px 2px;
    font-size: 13px;
    height: 25px;
    vertical-align: middle;
    min-width: 50px;
    font-weight: normal;
} 
.pnl-page-content button {
    width: auto!important;
    padding-left: 12px!important;
    padding-right: 12px!important;
    text-align: center;
}
.tr-item td {
    white-space: normal!important;
}
</style>
@endpush

@section('title_header')
All&nbsp;content
@endsection

@section('page_content')
<table style="width: 100%;" class="pnl-page-content"><tr><td>
<div style="float: left;margin-right: 3px;">
<a href="{{ route('skins.add') }}" type="button" class="btn btn-primary px-5 btn-my">Add</a>
</div>
<div style="float: left;margin-right: 3px;">
<form style="display: none;" id="form_file_import" action="{{ route('skins.import') }}" method="post" onchange="changeFileImport(this)" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
<input type="hidden" name="api_id" value="{{ $itemapi ? $itemapi->id : '' }}" />
<input type="file" name="file" id="inpt_file_import" />
</form>
<button onclick="addImportFile(this)" id="addImportFile" type="button" class="btn btn-secondary px-5 btn-my">Add file</button>
<button onclick="sendImportFile(this)" id="sendImportFile" style="display: none;" type="button" class="btn btn-warning px-5 btn-my">Send file ?</button>
</div>
@if(!\App\Helpers\Helper::isProcess())
<div style="float: left;margin-right: 3px;">
<div class="input-group">
  <label class="btn btn-info px-5 btn-my" style="padding-right: 12px!important;padding-left: 12px!important;" title="перевести заново существующие">
    <input id="translate_exists" type="checkbox" />
  </label>
  
  <button id="btnTranslateSkins" onclick="translateSkins(this)" type="button" class="btn btn-info px-5 btn-my">Translate&nbsp;All&nbsp;<span class="status"></span></button>
</div>
</div>
@endif
<div style="float: left;margin-right: 3px;">
<button onclick="deleteItems(this)" type="button" class="btn btn-danger px-5 btn-my">Delete</button>
</div>
</td></tr></table>
@if(isset($import_count_all) || isset($import_count_success))
<div>
Импорт:
@if(isset($import_count_all))
<div>
найдено: {{ $import_count_all }}
</div>
@endif()
@if(isset($import_count_success))
<div>
записано: {{ $import_count_success }}
</div>
@endif()
</div>
@endif()

@if(\App\Helpers\Helper::isProcess())
<div style="color: #B22222;">Запущен перевод текстов !!!</div>
@endif

				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="">
							<table id="example" class="table table-striped table-bordered" style="background-color: white!important;">
								<thead>
									<tr>
										<th style="width: 42px;text-align: center!important;" align="center"><input type="checkbox" id="select_items_all" /></th>
                                        <th></th>
										<th>
                                          <select name="search[api_id]" class="find_field" onchange="changeFindField(this)">
                                            <option value=""></option>
                                            @foreach($apis as $apikey => $api)
                                            <option value="{{ $api->id }}" @if(request('search.api_id') == $api->id) selected="" @endif>{{ $api->name }}</option>
                                            @endforeach
                                          </select>
                                        </th>
										<th></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.img')) }}" name="search[img]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th>
                                          <select name="search[category_id]" class="find_field" onchange="changeFindField(this)">
                                            <option value=""></option>
                                            @foreach($categories as $categorykey => $category)
                                            <option value="{{ $category->id }}" @if(request('search.category_id') == $category->id) selected="" @endif>{{ $category->name }}</option>
                                            @endforeach
                                          </select>
                                        </th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.downloads')) }}" name="search[downloads]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.likes')) }}" name="search[likes]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.views')) }}" name="search[views]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.min_version')) }}" name="search[min_version]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.file_size')) }}" name="search[file_size]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.price')) }}" name="search[price]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th colspan="2" rowspan="2"></th>
									</tr>
									<tr>
										<th valign="middle" style="width: 42px;text-align: center!important;" align="center"></th>
                                        <th valign="middle" style="text-align: center!important;"></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'api_id' ? '-' : '' }}api_id" href="?sort={{ $sort == 'api_id' ? '-' : '' }}api_id">Api</a></th>
										<th valign="middle" style="text-align: center!important;">Name</th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'img' ? '-' : '' }}img" href="?sort={{ $sort == 'img' ? '-' : '' }}img">Img</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'category_id' ? '-' : '' }}category_id" href="?sort={{ $sort == 'category_id' ? '-' : '' }}category_id">Category</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'downloads' ? '-' : '' }}downloads" href="?sort={{ $sort == 'downloads' ? '-' : '' }}img">Downloads</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'likes' ? '-' : '' }}likes" href="?sort={{ $sort == 'likes' ? '-' : '' }}likes">Likes</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'views' ? '-' : '' }}views" href="?sort={{ $sort == 'views' ? '-' : '' }}img">Views</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'min_version' ? '-' : '' }}min_version" href="?sort={{ $sort == 'min_version' ? '-' : '' }}min_version">Min Version</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'file_size' ? '-' : '' }}file_size" href="?sort={{ $sort == 'file_size' ? '-' : '' }}file_size">File Size</a></th>
										<th valign="middle" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'price' ? '-' : '' }}price" href="?sort={{ $sort == 'price' ? '-' : '' }}price">Price</a></th>
									</tr>
								</thead>
								<tbody>
									@foreach($items as $itemkey => $item)
                                    <tr class="tr-item" data-id="{{ $item->id }}">
										<td style="width: 42px;" align="center" valign="middle"><input type="checkbox" name="select_items[]" class="select_items" data-id="{{{ $item->id }}}" /></td>
                                        <td valign="middle" style="white-space: nowrap!important;width: 42px;">
                                         <span data-id="{{ $item->id }}" data-type="up" onclick="clickDisplaceItem(this)" style="cursor: pointer;">
                                           &nbsp;<i class="fa fa-long-arrow-up" aria-hidden="true"></i>&nbsp;
                                         </span>
                                         <span>
                                           &nbsp;
                                         </span>
                                         <span data-id="{{ $item->id }}" data-type="down"  onclick="clickDisplaceItem(this)" style="cursor: pointer;">
                                           &nbsp;<i class="fa fa-long-arrow-down" aria-hidden="true"></i>&nbsp;
                                         </span>
                                       </td>
									   <td valign="middle" align="center">{{ $item->api ? $item->api->name : '' }}</td>
                                       <td valign="middle">{{ $item->name }}</td>
                                       <td valign="middle" align="center">
                                       @if($item->firstImage())
                                       <a target="_blank" href="{{ $item->assetImage($item->firstImage()) }}">
                                         <img width="64" src="{{ $item->assetImageSmall($item->firstImage()) }}" />
                                       </a>
                                       @endif
                                       </td>
                                        <td valign="middle">
                                        @foreach($categories as $categorykey => $category)
                                        @if($category->id == $item->category_id)
                                        {{ $category->name }}
                                        @endif
                                        @endforeach
                                        </td>
                                       <td valign="middle" align="center">{{ $item->downloads }}</td>
                                       <td valign="middle" align="center">{{ $item->likes }}</td>
                                       <td valign="middle" align="center">{{ $item->views }}</td>
                                       <td valign="middle" align="center">{{ $item->min_version }}</td>
                                       <td valign="middle" align="center" style="white-space: nowrap;">{{ $item->getSizeFile() }}</td>
                                       <td valign="middle" align="center">{{ $item->price }}</td>
										<td style="width: 42px;" align="center" valign="middle"><a href="{{ route('skins.edit', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td style="width: 42px;" align="center" valign="middle"><a href="javascript:;" onclick="confirmClick(this)" data-href="{{ route('skins.delete', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-trash" aria-hidden="true"></i></a></td>
									</tr>
                                    @endforeach
								</tfoot>
							</table>
						</div>
                        {{ $items->links('paginate.index') }}
					</div>
				</div>
@endsection

@push('scripts')
<script>
$(".chosen-select").chosen();

function clickDisplaceItem(obj) {
    var $objToCheck = $('.select_items:checked:first');
    
    var objTo = null;
    
    if ($objToCheck.length) {
        objTo = $objToCheck[0].parentNode.parentNode;
        var item_to_id = $(objTo).attr('data-id');
    } else {
        item_to_id = 0;
    }
    
    $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "{{route('skins.displace_sort')}}",
		    	data: {
		    	    item_id: $(obj).attr('data-id'),  
		    	    item_to_id: item_to_id,  
		    	    type: $(obj).attr('data-type'),          
                },
                type: 'POST',
                dataType: 'json',
		    	beforeSend: function () {
		    	},
			    error: function (result) {
			        alert(JSON.stringify(result));
			    },
                success: function(result) {
                    if (objTo) {
                        var prevElem = null;
                        var thisElem = null;
                        var nextElem = null;
                        
                        $('.tr-item').each(function () {
                            if (prevElem && !nextElem) {
                                nextElem = this;
                            }
                            
                            if (!thisElem && $(obj).attr('data-id') == $(this).attr('data-id')) {
                                thisElem = this;
                            }
                        
                            if (!prevElem && objTo == this) {
                                prevElem = this;
                            }
                        });
                        
                        if (thisElem && nextElem && thisElem != nextElem) {
                            thisElem.parentNode.insertBefore(thisElem, nextElem);
                        }
                    } else {
                        var prevElem = null;
                        var thisElem = null;
                        var nextElem = null;
                    
                        $('.tr-item').each(function () {
                            if (thisElem && !nextElem) {
                                nextElem = this;
                            }
                        
                            if (!thisElem && $(obj).attr('data-id') == $(this).attr('data-id')) {
                                thisElem = this;
                            }
                        
                            if (!prevElem || !thisElem) {
                                prevElem = this;
                            }
                        });
                    
                        switch ($(obj).attr('data-type')) {
                            case 'up':
                                if (thisElem && prevElem && thisElem != prevElem) {
                                    thisElem.parentNode.insertBefore(thisElem, prevElem);
                                }
                                break;
                            case 'down':
                                if (thisElem && nextElem && thisElem != nextElem) {
                                    thisElem.parentNode.insertBefore(nextElem, thisElem);
                                }
                                break;
                        }
                    }
		    	},
   	});
}

function copyItems(obj) {
        var items_id = [];
        
        $(".select_items").each(function() {
            if($(this).prop('checked')){
                items_id.push($(this).attr('data-id'));
            }
        });
                
        if(items_id.length && confirm('Подтвердите действие')){
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/copy-select",
		    	data: {
		    	    items_id: items_id,
                    new_api_id: $("#new_api_id_sel").val(),
                    new_category_id: $("#new_api_id_category").val(),
                    new_language_id: $("#new_api_id_language").val(),
                },
                type: 'POST',
                dataType: 'json',
		    	beforeSend: function () {
		    	    $("#status_before_sel").show();
		    	    $("#status_success_sel").hide();
		    	},
			    error: function (result) {
			     //alert(JSON.stringify(result));
                 $('body').html(result['responseText']);
			    },
                success: function(result) {
		    	    $("#status_before_sel").hide();
		    	    $("#status_success_sel").show();
                    window.location.reload();
		    	},
	    	});
         }
}

function deleteItems(obj) {
    var items_id = [];
        
    $(".select_items").each(function() {
            if($(this).prop('checked')){
                items_id.push($(this).attr('data-id'));
            }
    });
    
    if(items_id.length && confirm('Вы действительно хотите удалить выбранные Читы ?')){
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/delete-select",
		    	data: {
		    	    items_id: items_id,
                    new_api_id: $("#new_api_id_sel").val(),        
                    new_category_id: $("#new_api_id_category").val(),          
                },
                type: 'POST',
                dataType: 'json',
		    	beforeSend: function () {
		    	},
			    error: function (result) {
			     //alert(JSON.stringify(result));
                 $('body').html(result['responseText']);
			    },
                success: function(result) {
                    window.location.reload();
		    	},
	    	});
    }
}

function copyItemsSV(obj) {
        var items_id = [];
        
        $(".select_items").each(function() {
            if($(this).prop('checked')){
                items_id.push($(this).attr('data-id'));
            }
        });
                
        if(items_id.length && confirm('Подтвердите действие')){
            $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/copy-sv-select",
		    	data: {
		    	    items_id: items_id,
                    new_api_id: $("#new_api_id_sel").val(),
                    new_category_id: $("#new_api_id_category").val(),
                    new_language_id: $("#new_api_id_language").val(),
                },
                type: 'POST',
                dataType: 'json',
		    	beforeSend: function () {
		    	    $("#status_before_sel").show();
		    	    $("#status_success_sel").hide();
		    	},
			    error: function (result) {
			     //alert(JSON.stringify(result));
                 $('body').html(result['responseText']);
			    },
                success: function(result) {
		    	    $("#status_before_sel").hide();
		    	    $("#status_success_sel").show();
                    window.location.reload();
		    	},
	    	});
         }
}

function addImportFile(obj) {
    $("#inpt_file_import").click();
}

function changeFileImport(obj) {
    $("#addImportFile").hide();
    $("#sendImportFile").show();
}

function sendImportFile(obj) {
    if (confirm("Вы действительно хотите отправить файл?")) {
        $("#form_file_import").submit();
    }
}

function translateSkins(obj) {
    if (confirm("Вы действительно запустить перевод?")) {
        $.ajax({
                headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken },
		    	url: "/admin/skins/translate",
		    	data: {
		    	    translate_exists: $("#translate_exists").prop('checked') ? 1 : 0,
                },
                type: 'POST',
                dataType: 'json',
		    	beforeSend: function () {
		    	    $("#btnTranslateSkins .status").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		    	},
			    error: function (result) {
			    },
                success: function(result) {
		    	    $("#btnTranslateSkins .status").html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
		    	},
   	    });
    }
}

</script>
@endpush