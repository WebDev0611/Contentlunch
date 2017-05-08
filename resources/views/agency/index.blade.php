@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="container-fluid">

        <h3 class="page-head">Clients Overview</h3>

        <!-- Dashboard Content -->
        <div class="content">

            <div class="row tight">
                <!-- Main Column -->
                <div class="col-md-12">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-forms" id="formError">
                            <p><strong>Oops! We had some errors:</strong>
                                <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </p>
                        </div>
                    @endif

                    @include('agency.partials.clients_list')
                </div> <!-- End Main Column -->

                {{--
                <div class="col-md-3">
                    @include('agency.partials.invited_guests_sidebar')
                </div>
                --}}
            </div>
        </div> <!-- End Dashboard Content -->
    </div>
</div>

@include('agency.partials.invite_guest_modal')
@stop

@section('scripts')
<script>
    (function() {
        $('#add-task-button').click(function() {
            add_task(function() {
                $('#addTaskModal').modal('hide');
            });
        });

        function showErrorFeedback(response) {
            $(loadIMG).remove();
            if (response.status === 403) {
                showUpgradeAlert(response);
            } else {
                swal('Error!', response, 'error');
            }
        }



        $('a.disable-account').click(function (e) {
            let accId = $(e.currentTarget).data('account_id');
            swal({
                type: 'warning',
                title: "Are you sure?",
                text:
                    `This subaccount will be disabled, and it's subscription will be cancelled.
                    This action cannot be undone.`,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, disable it",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise(resolve => {
                        $.post('/api/account/disable', {'account_id' : accId})
                            .done(function (data) {
                                swal({
                                    type: 'success',
                                    title: 'Account disabled!',
                                    html: 'Reloading the page...',
                                    showConfirmButton: false,
                                }).catch(swal.noop);

                                location.reload();

                                //resolve()
                            })
                    })
                }
            })
        });

        @if(Session::has('flash_message') && Session::has('flash_message_type') && session('flash_message_type') == 'danger')
            {!! 'showErrorFeedback("'.session('flash_message').'");' !!}
        @endif

    })();
</script>
@stop

