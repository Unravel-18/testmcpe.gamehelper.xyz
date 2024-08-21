@extends('layouts.app')

@section('title')
<title>Languages</title>
@endsection

@section('title_header')
Languages
@endsection

@section('page_content')
<table>
<tr>
<td>
<a href="{{ route('languages.add') }}" type="button" class="btn btn-primary px-5 btn-my">Add</a>
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
										<th><input style="width: 100%;" value="{{ urldecode(request('search.language')) }}" name="search[language]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.shortcode')) }}" name="search[shortcode]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th align="center"></th>
										<th><input style="width: 100%;" value="{{ urldecode(request('search.description')) }}" name="search[description]" onchange="changeFindField(this)" class="find_field" type="text" /></th>
										<th colspan="2" rowspan="2"></th>
									</tr>
									<tr>
										<th style="width: 42px;text-align: center!important;" align="center"></th>
                                        <th></th>
										<th><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'language' ? '-' : '' }}language" href="?sort={{ $sort == 'language' ? '-' : '' }}language">Language</a></th>
										<th><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'shortcode' ? '-' : '' }}shortcode" href="?sort={{ $sort == 'shortcode' ? '-' : '' }}shortcode">Shortcode</a></th>
										<th align="center" style="text-align: center!important;"><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'flag' ? '-' : '' }}flag" href="?sort={{ $sort == 'flag' ? '-' : '' }}flag">Flag</a></th>
										<th><a onclick="changeSortField(this)" data-name="sort" data-value="{{ $sort == 'description' ? '-' : '' }}description" href="?sort={{ $sort == 'description' ? '-' : '' }}description">Description</a></th>
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
										<td valign="middle">{{ $item->language }}</td>
                                        <td valign="middle">{{ $item->shortcode }}</td>
                                        <td valign="middle" align="center">
                                          @if ($item->flag)
                                          <img style="max-width:32px;" src="/images/{{ $item->flag }}"  />
                                          @endif
                                        </td>
                                        <td valign="middle">{{ Str::limit($item->description, 250, '...') }}</td>
                                        <td style="width: 42px;" align="center" valign="middle"><a href="{{ route('languages.edit', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-cog" aria-hidden="true"></i></a></td>
                                        <td style="width: 42px;" align="center" valign="middle"><a href="javascript:;" onclick="confirmClick(this)" data-href="{{ route('languages.delete', ['id' => $item->id]) }}"><i style="font-size: 16px;" class="fa fa-trash" aria-hidden="true"></i></a></td>
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
		    	url: "{{route('languages.displace_sort')}}",
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

</script>
@endpush