@extends('layouts.app')

@section('title', 'Comming Soon')

@section('content')
    <div class="comming-soon-pg w-100">
        <div class="coming-soon-box">
            <span>This Module is</span>
            <h1><span> COMING </span> SOON </h1>
            <p>Please check back later, We are working hard to get everything just right.</p>
            <ul class="coming-soon-timer">
                <li><span class="days">54</span>days</li>
                <li class="seperate-dot">:</li>
                <li><span class="hours">10</span>Hrs</li>
                <li class="seperate-dot">:</li>
                <li><span class="minutes">47</span>Min</li>
                <li class="seperate-dot">:</li>
                <li><span class="seconds">00</span>Sec</li>
            </ul>

            <ul class="social-media-icons">
                <li><a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-instagram"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-twitter"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-pinterest-p"></i></a></li>
                <li><a href="javascript:void(0);"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Datatable
            if($('.datatable-custom').length > 0) {
                var table = $('.datatable-custom').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search",
                        sLengthMenu: 'Rows Per Page _MENU_ Entries',
                        info: "_START_ - _END_ of _TOTAL_ items",
                        paginate: {
                            next: ' <i class=" fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> '
                        },
                    },
                    initComplete: (settings, json)=> {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                    },
                });

                $('#statusFilter').on('change', function() {
                    var selected = $(this).val();
                    table.column(1).search(selected).draw();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-breed').forEach(function(button) {
                button.addEventListener('click', function() {
                    var breedId = this.getAttribute('data-breed-id');
                    var breedName = this.getAttribute('data-breed-name');

                    var form = document.getElementById('editBreedForm');
                    form.action = '/admin/breeding/' + breedId;

                    // Set hidden and visible values
                    document.getElementById('edit-breed-id').value = breedId;
                    document.getElementById('edit-name').value = breedName;

                    document.getElementById('editBreedModalLabel').textContent = "Edit Breed - " + breedName;

                    // Show the modal
                    var modal = new bootstrap.Modal(document.getElementById('editBreedModal'));
                    modal.show();
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteId = null;

            document.querySelectorAll('.open-delete-modal').forEach(function(el) {
                el.addEventListener('click', function() {
                    deleteId = this.getAttribute('data-breed-id');
                    const breedName = this.getAttribute('data-breed-name');
                    document.getElementById('delete-modal-message').textContent =
                        `Are you sure you want to delete "${breedName}" data?`;

                    var modal = new bootstrap.Modal(document.getElementById('delete-modal'));
                    modal.show();
                });
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (deleteId) {
                    document.getElementById('delete' + deleteId).submit();
                }
            });
        });
    </script>
@endpush
