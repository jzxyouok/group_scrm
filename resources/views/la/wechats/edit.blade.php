@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/wechats') }}">Wechat</a> :
@endsection
@section("contentheader_description", $wechat->$view_col)
@section("section", "Wechats")
@section("section_url", url(config('laraadmin.adminRoute') . '/wechats'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Wechats Edit : ".$wechat->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
    <div class="box-header">
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($wechat, ['route' => [config('laraadmin.adminRoute') . '.wechats.update', $wechat->id ], 'method'=>'PUT', 'id' => 'wechat-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'app_id')
					@la_input($module, 'secret')
					@la_input($module, 'token')
					@la_input($module, 'aes_key')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/wechats') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $("#wechat-edit-form").validate({
        
    });
});
</script>
@endpush
