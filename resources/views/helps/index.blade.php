@extends('layouts.app')

@section('title')
<title>Helps</title>
@endsection

@section('title_header')
Helps
@endsection

@section('page_content')
<table>
<tr>
<td>
<div style="float: left;margin-right: 3px;">
<a href="{{ route('helps.add') }}" type="button" class="btn btn-primary px-5 btn-my">Add</a>
</div>
</td>
</tr>
</table>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
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
										<th colspan="2"></th>
										<th colspan="2" rowspan="2"></th>
									</tr>
									<tr>
										<th style="width: 42px;text-align: center!important;" align="center"></th>
                                        <th></th>
										<th><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'api_id' ? '-' : '' }}api_id" href="?sort={{ $sort == 'api_id' ? '-' : '' }}api_id">Api</a></th>
										<th>Name</th>
										<th>Text</th>
									</tr>
								</thead>
								<tbody>
									@foreach($items as $itemkey => $item)
                                    <tr class="tr-item" data-id="{{ $item->id }}">
										<td style="width: 42px;" align="center" valign="middle"><input type="checkbox" name="select_items[]" class="select_items" data-id="{{{ $item->id }}}" /></td>
                                        <td style="white-space: nowrap;width: 42px;">
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
                                        <td valign="middle">{{ $item->api ? $item->api->name : '' }}</td>
										<td valign="middle">{{ $item->name }}</td>
                                        <td valign="middle">{{ Str::limit($item->text, 50, '...') }}</td>
                                        <td style="width: 42px;" align="center" valign="middle"><a href="{{ route('helps.edit', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td style="width: 42px;" align="center" valign="middle"><a href="javascript:;" onclick="confirmClick(this)" data-href="{{ route('helps.delete', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-trash" aria-hidden="true"></i></a></td>
									</tr>
                                    @endforeach
								</tfoot>
							</table>
						</div>
                        {{ $items->links('paginate.index') }}
					</div>
				</div>
@endsection

@push('styles')
<style>
.chosen-choices{
    min-height: 38px!important;
    width: 200px!important;
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
</style>
@endpush

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
		    	url: "{{route('helps.displace_sort')}}",
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
		    	url: "/admin/helps/copy-select",
		    	data: {
		    	    items_id: items_id,
                    new_api_id: $("#new_api_id_sel").val(),        
                    new_category_id: $("#new_api_id_category").val(),          
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
</script>
@endpush