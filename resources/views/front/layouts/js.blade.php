{!! HTML::script(front_url('js/jquery-3.1.1.min.js')) !!}
{!! HTML::script(front_url('js/bootstrap.min.js')) !!}
{!! HTML::script(front_url('js/jquery.appear.min.js')) !!}
{!! HTML::script(front_url('js/jquery.incremental-counter.js')) !!}
{!! HTML::script(front_url('js/script.js')) !!}
{!! HTML::script(front_url('js/jquery.validate.min.js')) !!}
{!! HTML::script(front_url('js/bootstrap-notify.min.js')) !!}
{!! HTML::script(front_url('js/sweetalert2.all.min.js')) !!}
@stack('front_js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}',
            // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
