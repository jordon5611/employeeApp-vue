@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
       title: "{{ __('popups.error.title') }}",
        text: '{{ session('error') }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@elseif (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: "{{ __('popups.success.title') }}",
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
    });
</script>
@endif