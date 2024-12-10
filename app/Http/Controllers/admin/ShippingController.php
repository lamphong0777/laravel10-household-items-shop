<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('searchText')) {
            $searchText = $request->get('searchText');
            $provinceIdArray = Province::where('name', 'LIKE', "%{$searchText}%")->pluck('id')->toArray();
            $shipping_charges = ShippingCharge::whereIn('province_id', $provinceIdArray)->paginate(10);
        } else {
            $shipping_charges = ShippingCharge::paginate(10);
        }

        $provinces = Province::all();

        $data['provinces'] = $provinces;
        $data['shipping_charges'] = $shipping_charges;
        return view('admin.shipping.index', $data);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|unique:shipping_charges',
            'shipping_cost' => 'required|numeric',
        ], [
            'province_id.required' => 'Tỉnh thành không được trống.',
            'province_id.unique' => 'Tỉnh thành đã tồn tại.',
            'shipping_cost.required' => 'Phí vận chuyển không được trống.',
            'shipping_cost.numeric' => 'Phí vận chuyển không hợp lệ.',
        ]);

        if ($validator->passes()) {
            // Save data to database
            $shipping = new ShippingCharge();
            $shipping->province_id = $request->province_id;
            $shipping->shipping_cost = $request->shipping_cost;
            $shipping->save();

            $request->session()->flash('success', 'Shipping created successfully!');
            return redirect()->route('admin.shipping.index');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function edit($id)
    {
        $shipping_charge = ShippingCharge::find($id);
        $provinces = Province::all();
        $data['shipping_charge'] = $shipping_charge;
        $data['provinces'] = $provinces;
        return view('admin.shipping.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|unique:shipping_charges,province_id,' . $id,
            'shipping_cost' => 'required|numeric',
        ], [
            'province_id.required' => 'Tỉnh thành đã không được trống.',
            'province_id.unique' => 'Tỉnh thành đã tồn tại.',
            'shipping_cost.required' => 'Phí vận chuyển không được trống.',
            'shipping_cost.numeric' => 'Phí vận chuyển không hợp lệ.',
        ]);

        if ($validator->passes()) {
            ShippingCharge::find($id)->update($request->all());
            $request->session()->flash('success', 'Shipping updated successfully!');
            return redirect()->route('admin.shipping.index');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $shipping_charge = ShippingCharge::find($id);
        if (empty($shipping_charge)) {
            $request->session()->flash('error', 'Shipping charge not found.');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Shipping charge not found.'
            ]);
        }
        $shipping_charge->delete();

        $request->session()->flash('success', 'Shipping charge deleted successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Shipping charge deleted successfully.'
        ]);
    }
}