@extends('layouts.app')

@section('title', $bill->user->name)
@section('content')
  <div class="row">

    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
      <div class="card ">
        <div class="card-body">
          <div class="text-center">
            创建人：{{ $bill->user->name }}
          </div>
          <hr>
          <div class="media">
            <div align="center">
              <a href="{{ route('users.show', $bill->user->id) }}">
                <img class="thumbnail img-fluid" src="{{ $bill->user->avatar }}" width="300px" height="300px">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
      <div class="card ">
        <div class="card-body">
          <h2 class="text-center mt-3 mb-3">
            {{ $bill->created_at }}
          </h2>

          {{--<div class="article-meta text-center text-secondary">--}}
            {{--{{ $bill->created_at->diffForHumans() }}--}}
            {{--⋅--}}
          {{--</div>--}}

          <div class="topic-body mt-4 mb-4">
            {!! $bill->tips !!}
          </div>

          <div class="topic-body mt-4 mb-4">
            <b>付款金额： {{$bill->total_bill}}</b>
          </div>
          <div class="topic-body mt-4 mb-4">
            <b>付款人： {{$bill->paymentUser->name}}</b>
          </div>
          @if (count($bill->billUsers))
            <ul class="list-group">
              @foreach ($bill->billUsers as $billUser)
                <li class="list-group-item list-group-item-success">
                  <span >{{$billUser->user->name}}</span>
                  <span>花费金额：{{$billUser->cost}}</span>
                </li>
              @endforeach
            </ul>

          @endif

          @can('update', $bill)
            <div class="operate">
              <hr>
              <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-outline-secondary btn-sm" role="button">
                <i class="far fa-edit"></i> 编辑
              </a>
              <form action="{{ route('bills.destroy', $bill->id) }}" method="post"
                    style="display: inline-block;"
                    onsubmit="return confirm('您确定要删除吗？');">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                  <i class="far fa-trash-alt"></i> 删除
                </button>
              </form>
            </div>
          @endcan

        </div>
      </div>
    </div>
  </div>
@endsection
