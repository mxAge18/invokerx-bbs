@extends('layouts.app')

@section('content')

<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-header">
        <h2 class="">
            <i class="far fa-edit"></i>
          Bill /
          @if($bill->id)
            编辑{{ $bill->id }}
          @else
            创建
          @endif
        </h2>
      </div>

      <div class="card-body">
        @if($bill->id)
          <form action="{{ route('bills.update', $bill->id) }}" method="POST" accept-charset="UTF-8">
          <input type="hidden" name="_method" value="PUT">
        @else
          <form action="{{ route('bills.store') }}" method="POST" accept-charset="UTF-8">
        @endif

          @include('shared._error')

          <input type="hidden" name="_token" value="{{ csrf_token() }}">


                {{--<div class="form-group">--}}
                	{{--<label for="path-field">Path</label>--}}
                	{{--<input class="form-control" type="text" name="path" id="path-field" value="{{ old('path', $bill->path ) }}" />--}}
                {{--</div> --}}
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="bill_group_id-field">Bill分组选择</label>
                      <select class="form-control" name="bill_group_id" id="bill_group_id-field"  >
                          <option value="" hidden disabled {{ $bill->id ? '' : 'selected' }}>请选择分组</option>
                          @foreach ($group as $value)
                              <option value="{{ $value->id }}" {{ $value->id == $bill->bill_group_id ? 'selected' : '' }}>{{ $value->group_name }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="payment_user_id-field">Bill付款人</label>
                      <select class="form-control" name="payment_user_id" id="payment_user_id-field"  >
                          <option value="" hidden disabled {{ $bill->id ? '' : 'selected' }}>请选择分组</option>
                          @foreach ($user as $value)
                              <option value="{{ $value->user_id }}" {{ $value->user_id == $bill->payment_user_id ? 'selected' : '' }}>{{ $value->user->name }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>


              <div class="form-group">
                  <label for="total_bill-field">总花费：</label>
                  <input class="form-control" type="number" min="0.0" step="0.01" name="total_bill" id="total_bill-field" value="{{ old('total_bill', $bill->total_bill ) }}" />
              </div>
              <div class="form-group">
                  <textarea name="tips" class="form-control" id="editor" rows="6" placeholder="请填入至少三个字符的内容。" required>{{ old('tips', $bill->tips ) }}</textarea>
              </div>
              @if($bill->id)
                  @foreach ($bill->billDetails as $value)
                      <div class="form-group">
                          <label for="single_bill_user_{{$value->user_id}}">{{$value->user->name}}单独花费金额：</label>
                          <input class="form-control" type="number" min="0.0" step="0.001" name="single_bill_user_{{$value->user_id}}" id="single_bill_user_{{$value->user_id}}" value="{{$value->needs_single_pay}}" />
                      </div>
                  @endforeach
                  @else
                      @foreach ($user as $value)
                          <div class="form-group">
                              <label for="single_bill_user_{{$value->user_id}}">{{$value->user->name}}单独花费金额：</label>
                              <input class="form-control" type="number" min="0.0" step="0.01" name="single_bill_user_{{$value->user_id}}" id="single_bill_user_{{$value->user_id}}" value="single_bill_user_{{$value->user_id}}" />
                          </div>
                      @endforeach
              @endif

                <div class="form-group">
                    <input class="form-control" type="hidden" name="is_needs_compute" id="is_needs_compute-field" value="2" />
                </div>

          <div class="well well-sm">
            <button type="submit" class="btn btn-primary">提交Bill</button>
            <a class="btn btn-link float-xs-right" href="{{ route('bills.index') }}"> <- 返回</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(document).ready(function() {
            var editor = new Simditor({
                textarea: $('#editor'),
                upload: {
                    url: '{{ route('bills.upload_image') }}',
                    params: {
                        _token: '{{ csrf_token() }}'
                    },
                    fileKey: 'upload_file',
                    connectionCount: 3,
                    leaveConfirm: '文件上传中，关闭此页面将取消上传。'
                },
                pasteImage: true,
            });
        });
    </script>
@stop