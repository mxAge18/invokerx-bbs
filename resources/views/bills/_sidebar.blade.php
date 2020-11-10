<div class="card ">
    <div class="card-body">
        <a href="{{ route('bills.create') }}" class="btn btn-success btn-block" aria-label="Left Align">
            <i class="fas fa-pencil-alt mr-2"></i>  记账
        </a>
    </div>
</div>
@if (count($billGroupUsers))
    <div class="card mt-4">
        <div class="card-body active-users pt-2">
            <div class="text-center mt-1 mb-0 text-muted">付款情况</div>
            <hr class="mt-2">
                <div class="media mt-2" >
                    <table class="table  table-bordered table-condensed sidebar-table">
                        <thead>
                        <tr>
                            <th style="padding:0 !important;">User</th>
                            <th style="padding:0 !important;">支付</th>
                            <th style="padding:0 !important;">消费</th>
                            <th style="padding:0 !important;">结余</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($billGroupUsers as $billGroupUser)
                        <tr>
                            <td class="sidebar-table" style="padding:0 !important;"><div class="media-left media-middle mr-2 ml-1">
                                    <img src="{{ $billGroupUser->user->avatar }}" width="24px" height="24px" class="media-object">
                                    <br><span>{{$billGroupUser->user->name}}</span>
                                </div></td>
                            <td class="sidebar-table" style="padding:0 !important;"><small class="media-heading text-secondary">{{ $billGroupUser->user_payment_number }}</small></td>
                            <td class="sidebar-table" style="padding:0 !important;"><small class="media-heading text-secondary">{{ $billGroupUser->user_cost_number }}</small></td>
                            <td class="sidebar-table" style="padding:0 !important;"><small class="media-heading text-secondary">{{ $billGroupUser->user_overdraft }}</small></td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

        </div>
    </div>
@endif

@if (count($links))
    <div class="card mt-4">
        <div class="card-body pt-2">
            <div class="text-center mt-1 mb-0 text-muted">推荐链接</div>
            <hr class="mt-2 mb-3">
            @foreach ($links as $link)
                <a class="media mt-1" href="{{ $link->link }}">
                    <div class="media-body">
                        <span class="media-heading text-muted">{{ $link->title }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif