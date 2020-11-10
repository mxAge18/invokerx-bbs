<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Jobs\ComputeBillCost;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\BillGroup;
use App\Models\BillGroupUser;
use App\Models\BillUser;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Bill $bill, Link $link, BillGroupUser $billGroupUser)
	{
		$bills = $bill->withOrder($request->order)
            ->with('user', 'paymentUser', 'billUsers.user', 'billDetails.user')
            ->paginate(5);

        $links = $link->getAllCached();
        $billGroupUsers = $billGroupUser->getAllCached();
		return view('bills.index', compact('bills', 'links', 'billGroupUsers'));
	}

    public function show(Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

	public function create(Bill $bill)
	{

        $group = BillGroup::with('billGroupUsers.user')
            ->whereIn('id', function ($query) {
                $query->select('bill_group_id')
                    ->from('bill_group_users')
                    ->where('user_id', auth()->id());
            })->get();
        $user = BillGroupUser::with('user')
            ->where('bill_group_id', 2)
            ->get();

		return view('bills.create_and_edit', compact('bill', 'group', 'user'));
	}

	public function store(BillRequest $request, Bill $bill)
	{
        DB::beginTransaction();

        $bill->fill($request->all());
        $bill->user_id = Auth::id();
        $bill->is_needs_compute = Bill::BILL_NEEDS_COMPUTE;
        $bill->save();
        $billUser = [];
        $billDetail = [];
        $user = BillGroupUser::select('user_id')
            ->where('bill_group_id', $bill->bill_group_id)
            ->get()->toArray();
        foreach ($user as $value) {
            array_push($billDetail,
                new BillUser([
                    'user_id' => $value['user_id'],
                    'cost' => 0,
                    'tips' => '']));
            array_push($billUser, new BillDetail([
                'user_id' => $value['user_id'],
                'needs_single_pay' => $request['single_bill_user_' . $value['user_id']],
                'tips' => '']));
        }

        $bill->billDetails()->saveMany($billDetail);
        $bill->billUsers()->saveMany($billUser);
        DB::commit();
        dispatch(new ComputeBillCost($bill));
		return redirect()->route('bills.show', $bill->id)->with('success', 'Bill创建成功.');
	}

	public function edit(Bill $bill)
	{
        $group = BillGroup::with('billGroupUsers.user')
            ->whereIn('id', function ($query) {
                $query->select('bill_group_id')
                    ->from('bill_group_users')
                    ->where('user_id', auth()->id());
            })->get();
        $user = BillGroupUser::with('user')
            ->where('bill_group_id', 2)
            ->get();
        $this->authorize('update', $bill);


		return view('bills.create_and_edit', compact('bill', 'group', 'user'));
	}

	public function update(BillRequest $request, Bill $bill)
	{
		$this->authorize('update', $bill);
		$billDetails = BillDetail::where([
		    ['bill_id', $bill->id],
        ])->get();
//
        foreach ($billDetails as $value) {
            $value->needs_single_pay = $request['single_bill_user_' . $value->user_id];
            $value->update();
        }
        $bill->fill($request->all());
        $bill->is_needs_compute = Bill::BILL_NEEDS_COMPUTE;
        $bill->update();
        dispatch(new ComputeBillCost($bill));
		return redirect()->route('bills.show', $bill->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Bill $bill)
	{
		$this->authorize('destroy', $bill);
		$bill->delete();
        dispatch(new ComputeBillCost($bill));
		return redirect()->route('bills.index')->with('message', 'Deleted successfully.');
	}

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];

        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}