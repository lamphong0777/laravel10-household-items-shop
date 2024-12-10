@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><span class="text-primary">Tài khoản</span></li>
                        <li class="breadcrumb-item">Thông tin tài khoản</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="section-11">
            <div class="container  mt-2">
                <div class="row">
                    @if (Session::has('success'))
                        <div class="alert">
                            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Thông tin tài khoản</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <form action="" id="updateInformForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="account_name">Tên tài khoản</label>
                                            <input type="text" name="account_name" id="account_name"
                                                placeholder="Enter Your Name" class="form-control"
                                                value="{{ $user->name }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_email">Email đăng nhập</label>
                                            <input type="text" name="account_email" id="account_email"
                                                placeholder="Enter Your Email" class="form-control"
                                                value="{{ $user->email }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="account_phone">Số điện thoại</label>
                                            <input type="text" name="account_phone" id="account_phone"
                                                placeholder="Enter Your Phone" class="form-control"
                                                value="{{ $user->phone }}">
                                            <p></p>
                                        </div>

                                        <div class="d-flex">
                                            <button class="btn btn-dark" type="submit">Cập nhật tài khoản</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--                    cusomer address --}}
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Thông tin đặt hàng</h2>
                            </div>
                            <div class="card-body p-4">
                                <form action="" id="updateOrderAddressForm">
                                    @csrf
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label for="name">Tên khách hàng</label>
                                            <input type="text" name="name" id="name"
                                                placeholder="Enter Your Name" class="form-control"
                                                value="{{ $customer_order_info ? $customer_order_info->name : '' }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email"
                                                placeholder="Enter Your Email" class="form-control"
                                                value="{{ $customer_order_info ? $customer_order_info->email : '' }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="phone">Điện thoại</label>
                                            <input type="text" name="phone" id="phone"
                                                placeholder="Enter Your Phone" class="form-control"
                                                value="{{ $customer_order_info ? $customer_order_info->phone : '' }}">
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="province">Tỉnh</label>
                                            <select name="province" id="province" class="form-control">
                                                <option value="">- Tỉnh thành -</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        @if ($customer_order_info) @if ($province->id == $customer_order_info->province_id) selected @endif
                                                        @endif>{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="district">Quận/Huyện</label>
                                            <select name="district" id="district" class="form-control">
                                                <option value="">- Quận/huyện -</option>
                                                @if (isset($districts))
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            @if ($customer_order_info) @if ($customer_order_info->district_id == $district->id) selected @endif
                                                            @endif
                                                            >{{ $district->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="ward">Xã/Phường</label>
                                            <select name="ward" id="ward" class="form-control">
                                                <option value="">- Xã/phường -</option>
                                                @if (isset($wards))
                                                    @foreach ($wards as $ward)
                                                        <option value="{{ $ward->id }}"
                                                            @if ($customer_order_info) @if ($customer_order_info->ward_id == $ward->id) selected @endif
                                                            @endif
                                                            >{{ $ward->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label for="phone">Địa chỉ</label>
                                            <textarea name="address" id="address" class="form-control" cols="30" rows="3"
                                                placeholder="Enter Your Address">{{ $customer_order_info ? $customer_order_info->address : '' }}</textarea>
                                            <p></p>
                                        </div>

                                        <div class="d-flex">
                                            <button class="btn btn-dark" type="submit">Cập nhật địa chỉ</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script>
        $("#province").change(function() {
            let province_id = $(this).val();
            $("#discount_value_show").html(0);
            $("#discount_code").val("");
            $.ajax({
                url: '{{ route('shop.district') }}',
                type: 'get',
                dataType: 'json',
                data: {
                    province_id: province_id
                },
                success: (response) => {
                    $("#district").find("option").not(":first").remove();
                    $("#ward").find("option").not(":first").remove();
                    $.each(response["districts"], (key, item) => {
                        $("#district").append(
                            `<option value="${item.id}">${item.name}</option>`)
                    });
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });

        });


        $("#district").change(function() {
            let district_id = $(this).val();

            $.ajax({
                url: '{{ route('shop.ward') }}',
                type: 'get',
                dataType: 'json',
                data: {
                    district_id: district_id
                },
                success: (response) => {
                    $("#ward").find("option").not(":first").remove();
                    $.each(response["wards"], (key, item) => {
                        $("#ward").append(`<option value="${item.id}">${item.name}</option>`)
                    });
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });
        });


        $("#updateInformForm").submit(function(e) {
            e.preventDefault();
            const url = '{{ route('user.update-profile') }}';
            let data = $(this).serializeArray();
            ajax_post(url, data);
        });

        $("#updateOrderAddressForm").submit(function(e) {
            e.preventDefault();
            const url = '{{ route('user.update-address') }}';
            let data = $(this).serializeArray();
            ajax_post(url, data);
        });


        function ajax_post(url, data) {
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data,
                success: (response) => {
                    if (response["status"]) {
                        window.location.href = '{{ route('user.profile', Auth::id()) }}'
                    } else {
                        const errors = response["errors"];
                        $("input[type='text'], input[type='password']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        });
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            });
        }
    </script>
@endsection
