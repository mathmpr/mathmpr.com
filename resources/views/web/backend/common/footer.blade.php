@include('web.loader')
@auth
    <script>
        window.apiToken = "{{ request()->cookies->get('api-key') }}";
    </script>
@endauth
