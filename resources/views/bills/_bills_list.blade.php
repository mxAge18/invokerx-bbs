@if (count($bills))
    <div class="table-responsive">
        <table class="table table-bill table-hover table-bordered table-condensed">
            <thead>
            <tr class="success text-center">
                <th>支付人</th>
                <th class="hidden-xs text-center success">记账时间</th>
                <th>支付金额</th>
                    @foreach ($bills[0]->billUsers as $billUser)
                    <th class="dl-horizontal" style="width: 80px;">
                        <span style="width: 80px;">{{$billUser->user->name}}</span>
                    </th>
                    @endforeach
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($bills as $bill)
                <tr class="text-center text-primary">
                    <td>
                        <div class="media-left text-left" >
                            <a href="{{ route('users.show', [$bill->paymentUser->id]) }}">
                                <img class="media-object img-thumbnail mr-3" style="width: 40px; height: 40px;" src="{{ $bill->paymentUser->avatar }}" title="{{ $bill->user->name }}">
                            </a>
                            <span>{{$bill->paymentUser->name}}</span>
                        </div>
                    </td>
                    <td class="media-object">{{$bill->created_at}} </td>
                    <td><a href="{{ $bill->link() }}">{{$bill->total_bill}}</a></td>
                    @foreach ($bill->billUsers as $billUser)
                        <td style="width: 80px;">
                            <span>{{$billUser->cost}}</span>
                        </td>
                    @endforeach
                    <td>
                        <a href="{{ $bill->link() }}">
                            <button type="button" class="btn btn-xs btn-info">Detail.</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="empty-block">暂无数据 ~_~ </div>
@endif