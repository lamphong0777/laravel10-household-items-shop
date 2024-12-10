@extends('front.layouts.app')
@section('title')
    {{ $staticPage->name }}
@endsection
@section('content')
    <h1 class="text-center text-primary text-uppercase fw-bolder bg-white p-2">{{ $staticPage->name }}</h1>
    <div class="container border border-3 border-secondary p-3 rounded">

        <div class="row">
            {!! $staticPage->content !!}
        </div>
        @if ($staticPage->slug == 'lien-he')
            <div class="row">
                <div class="col-md-6">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15121.89037793865!2d105.77228770389917!3d10.025891940173782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0895a51d60719%3A0x9d76b0035f6d53d0!2zxJDhuqFpIGjhu41jIEPhuqduIFRoxqE!5e0!3m2!1svi!2s!4v1729096321572!5m2!1svi!2s"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="col-md-6">
                    <form action="" id="sendContactForm">
                        @csrf
                        <div class="mb-3">
                            <label class="mb-2" for="name">Tên</label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="Tên...">
                            <div class="help-block with-errors fs-7"></div>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2" for="email">Email</label>
                            <input class="form-control" id="email" type="email" name="email" placeholder="Email...">
                            <div class="help-block with-errors fs-7"></div>
                        </div>

                        <div class="mb-3">
                            <label class="mb-2">Tiêu đề</label>
                            <input class="form-control" id="subject" type="text" name="subject"
                                placeholder="Tiêu đề...">
                            <div class="help-block with-errors fs-7"></div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="mb-2">Nội dung</label>
                            <textarea class="form-control" rows="3" id="message" name="message" placeholder="Nội dung..."></textarea>
                            <div class="help-block with-errors fs-7"></div>
                        </div>

                        <div class="form-submit">
                            <button class="btn btn-dark" type="submit" id="form-submit"><i
                                    class="material-icons mdi mdi-message-outline"></i> Gửi</button>
                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script>
        $('#sendContactForm').submit(function(e) {
            e.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('shop.send-contact') }}',
                type: 'post',
                dataType: 'json',
                data: $(this).serializeArray(),
                success: (response) => {
                    $("button[type='submit']").prop('disabled', false);
                    if (response["status"]) {
                        Swal.fire({
                            title: "Đã gửi!",
                            text: "Chúng tôi sẽ phản hồi bạn sớm nhất!",
                            icon: "success"
                        });
                    } else {
                        const errors = response["errors"];
                        $("input[type='text'], input[type='password']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('div').addClass(
                                'invalid-feedback').html(value);
                        });
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        })
    </script>
@endsection
