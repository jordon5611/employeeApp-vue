@props(['data', 'editRoute', 'deleteRoute', 'detailsRoute' => null])

<!-- <tr class="hover:bg-gray-700 cursor-pointer" @if ($detailsRoute)
onclick="window.location='{{ route($detailsRoute, $data['id']) }}'" @endif> -->
@foreach ($data as $cell)
    <td class="border border-white px-4 py-2">{{ $cell }}</td>
@endforeach
<td class="border border-white px-4 py-2">
    <div class="flex gap-1">
        @if ($detailsRoute)
            <a href="{{ route($detailsRoute, $data['id']) }}"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">@lang('components.show')</a>
        @endif
        <a href="{{ route($editRoute, $data['id']) }}"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">@lang('components.edit')</a>
        <form action="{{ route($deleteRoute, $data['id']) }}" method="POST" class="inline-block delete-form">
            @csrf
            @method('DELETE')
            <button type="button"
                class="delete-btn bg-red-500 text-white px-3 py-2 rounded hover:bg-red-700 transition duration-300">
                @lang('components.delete')
            </button>
        </form>
    </div>
</td>
</tr>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default form submission

                const form = this.closest('.delete-form'); // Get the closest form

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: true
                });

                swalWithBootstrapButtons.fire({
                    title: "{{ __('popups.delete_confirmation.title') }}",
                    text: "{{ __('popups.delete_confirmation.text') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('popups.delete_confirmation.confirm_button') }}",
                    cancelButtonText: "{{ __('popups.delete_confirmation.cancel_button') }}",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        form.submit();

                        // swalWithBootstrapButtons.fire({
                        //     title: "Deleted!",
                        //     text: "Your record has been deleted.",
                        //     icon: "success"
                        // });
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire({
                            title: "{{ __('popups.delete_cancel.title') }}",
                            text: "{{ __('popups.delete_cancel.text') }}",
                            icon: "error"
                        });
                    }
                });
            });
        });
    });
</script>