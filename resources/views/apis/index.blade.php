@extends('layouts.app')

@section('title')
<title>Apis</title>
@endsection

@section('title_header')
Apis
@endsection

@section('page_content')
<table>
<tr>
<td>
<a href="{{ route('apis.add') }}" type="button" class="btn btn-primary px-5 btn-my">Add</a>
</td>
</tr>
</table>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="row row-cols-auto row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
							@foreach($items as $itemkey => $item)
                            <div class="col">
								<div class="p-4 text-center">
                                  <table style="margin-left: auto;margin-right: auto;">
                                    <tr>
                                      <td>
                                        <img width="128" src="{{$item->img ?  asset('/images/api/' . $item->img) :  asset('/img/2620998.png') }}" />
                                      </td>
                                      <td align="left">
                                        <div>
                                          <a href="{{ route('apis.skins', ['id' => $item->id]) }}">
                                            {{ $item->name }}({{ $item->shortcode }})
                                          </a>
                                        </div>
                                        <div>
                                          &nbsp;
                                        </div>
                                        <div>
                                          <a href="{{ route('apis.edit', ['id' => $item->id]) }}">Settings</a>
                                        </div>
                                        <div>
                                          <a href="javascript:;" onclick="confirmClick(this)" data-href="{{ route('apis.delete', ['id' => $item->id]) }}">Delete</a>
                                        </div>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
							</div>
                            @endforeach
						</div>
                        
                        {{ $items->links('paginate.index') }}
                        
						<!--end row-->
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
</script>
@endpush