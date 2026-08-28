@include('web.loader')
@auth
    <script>
        window.apiToken = "{{ request()->cookies->get('api-key') }}";
        window.csrfToken = "{{ request()->session()->token() }}";
    </script>
@endauth
