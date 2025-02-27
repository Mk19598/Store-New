<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .disabled-link {
        color: gray !important; 
        pointer-events: none; 
        cursor: not-allowed; 
    }
</style>
<div class="table-responsive" >
    <table class="table table-striped" id="orders-list-table">

        <thead>
            <tr>
                <th align="center"> {{ ucwords(__('Select')) }}  </th>
                <th align="center">#</th>
                <th align="center">{{ ucwords(__('Order ID')) }} </th>
                <th align="center">{{ ucwords(__('Order IN')) }} </th>
                <th align="center">{{ ucwords(__('Customer')) }} </th>
                <th align="center">{{ ucwords(__('mobile number')) }} </th>
                <th align="center">{{ ucwords(__('Date & Time')) }} </th>
                <th align="center">{{ ucwords(__('Status')) }}  </th>
                <th align="center">{{ ucwords(__('Payment Status')) }}  </th>
                <th align="center">{{ ucwords(__('Total')) }}   </th>
                <th align="center">{{ ucwords(__('Actions')) }} </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $key => $order)
                <tr>
                    <td align="center"> <input class="form-check-input order-checkbox" type="checkbox" value="{{ $order->id }}"  data-uuid="{{ $order->uuid }}"></td>
                    <td align="center"> {{ $key+1 }} </td>
                    <td align="center"> {{ @$order->order_id }} </td>
                    <td align="center"> {{ @$order->order_vai }} </td>
                    <td align="center">
                        <div>{{ ucwords(@$order->buyer_first_name) }}</div>
                        <div class="text-muted">{{ @$order->buyer_email }}</div>
                    </td>
                    <td align="center">{{ @$order->buyer_mobile_number }}</td>
                    <td align="center"> {{ @$order->order_created_at_format }}</td>
                    <td align="center"> <span class="badge bg-{{ $order->status_color }}">{{ ucwords(@$order->status) }}</span></td>
                    <td align="center">
                        {!! (!empty($order->PaymentId) && $order->payment_mode == 'razorpay' && $order->PaymentId !== 'processing') 
                            ? '<a href="javascript:void(0);" class="verify-payment" data-payment-id="'.$order->PaymentId.'">
                                    <i class="bi bi-credit-card"></i> Verify
                            </a>' 
                            : '—' !!}
                    </td>
                    <td align="center"> {{ @$order->currency_symbol.number_format(@$order->total_cost, 2) }}</td>
                    <td align="center">
                        <div class="action-icons" style="display: flex; justify-content: center; gap: 10px; align-items: center;">
                            <a href="{{ $order->status == 'Packed'  ?  route('orders.invoice_pdf',$order->order_uuid) : 'javascript:void(0);' }}"
                                class="{{  $order->status == 'Packed'  ? '' : 'disabled-link' }}" target="_blank">
                                <i class="bi bi-receipt"></i>
                            </a>
                       
                            <a href="{{  $order->status == 'Packed'  ? route('orders.shipping_label_pdf', $order->order_uuid) : 'javascript:void(0);' }}"
                                class="{{  $order->status == 'Packed'  ? '' : 'disabled-link' }}">
                                    <i class="bi bi-tag"></i>
                            </a>

                            <a href="javascript:void(0);" class="add-tracking-link-btn" data-order-id="{{ $order->order_uuid }}">
                                <i class="bi bi-geo-alt"></i>
                            </a>
                            <a href="javascript:void(0);" class="add-order-note-btn" data-order-id="{{ $order->order_uuid }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>               
</div>

<!-- Modal -->
<div class="modal fade" id="trackingLinksModal" tabindex="-1" aria-labelledby="trackingLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackingLinksModalLabel">Add Tracking Links</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.tracking_links') }}" id="trackingLinksForm">
                @csrf
          
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="courier" class="form-label">Select Courier ID</label>
                        <select class="form-select" id="courier_id" name="courier_id">
                            <option value="" disabled selected>Choose a courier</option>
                            <option value="58">ST Courier</option>
                            <option value="2">Delhivery</option>
                            <option value="7">DTDC</option>
                            <option value="673">Borzo</option>
                        </select>
                    </div>
                    <div id="tracking-links-container">
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="add-tracking-link">Add More</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="save-tracking-links">Save</button>
                </div>
            </form> 
        </div>
    </div>
</div>


<!-- Notes Modal -->
<div class="modal fade" id="orderNotesModal" tabindex="-1" aria-labelledby="orderNotesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderNotesModalLabel">Add Order Notes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="orderNotesForm">
                @csrf
                <div class="modal-body">
                    <div id="order-notes-container"></div>
                    <button type="button" class="btn app-btn-primary" id="add-order-note">Add Note</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn app-btn-primary">Save Notes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

$(document).ready(function() {
    let baseURL = "{{ URL::to('/') }}";

    $(document).on("click", ".add-tracking-link-btn", function() {
        let orderId = $(this).data("order-id");

        $.ajax({
            url: `${baseURL}/orders/tracking-links/${orderId}`,
            method: "GET",
            success: function(response) {
                let modalBody = $("#tracking-links-container");
                modalBody.empty();

                let courierSelect = $("#courier_id");
                courierSelect.val(response.courier_id || "");

                let trackingLinks = response.tracking_links || [];
                let trackingLinkCount = trackingLinks.length;

                if (trackingLinkCount > 0) {
                    trackingLinks.forEach((link, index) => {
                        modalBody.append(`
                            <div class="mb-3 tracking-item">
                                <label for="tracking-link-${index + 1}" class="form-label">Tracking Link ${index + 1}</label>
                                <input type="text" class="form-control" id="tracking-link-${index + 1}" name="tracking_links[]" value="${link}" placeholder="Enter tracking link">
                            </div>
                        `);
                    });
                } else {
                    modalBody.append(`
                        <div class="mb-3 tracking-item">
                            <label for="tracking-link-1" class="form-label">Tracking Link</label>
                            <input type="text" class="form-control" id="tracking-link-1" name="tracking_links[]" placeholder="Enter tracking link">
                        </div>
                    `);
                    trackingLinkCount = 1;
                }

                $("#add-tracking-link").data("count", trackingLinkCount);

                $("#trackingLinksForm input[name='order_id']").remove();
                $("#trackingLinksForm").append(`<input type="hidden" name="order_id" value="${orderId}">`);
                $("#trackingLinksModal").modal("show");
            },
            error: function(error) {
                console.error("Error fetching tracking links:", error);
                alert("An error occurred while fetching tracking links.");
            }
        });
    });

    $(document).off("click", "#add-tracking-link").on("click", "#add-tracking-link", function() {
        let trackingContainer = $("#tracking-links-container");
        let trackingLinkCount = parseInt($(this).data("count")) || 1;

        trackingLinkCount++; 

        trackingContainer.append(`
            <div class="mb-3 tracking-item">
                <label for="tracking-link-${trackingLinkCount}" class="form-label">Tracking Link ${trackingLinkCount}</label>
                <input type="text" class="form-control" id="tracking-link-${trackingLinkCount}" name="tracking_links[]" placeholder="Enter tracking link">
            </div>
        `);

        $(this).data("count", trackingLinkCount);
    });

    $("#trackingLinksForm").on("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        formData.append("_token", $("meta[name='csrf-token']").attr("content"));

        $.ajax({
            url: "{{ route('orders.tracking_links') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success("Tracking Links added successfully!", "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(error) {
                toastr.error("An error occurred while saving tracking links.", "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
            }
        });
    });
});
  
$(document).ready(function() {
    let baseURL = "{{ URL::to('/') }}";

    $(document).on("click", ".add-order-note-btn", function() {
        let orderId = $(this).data("order-id");

        $.ajax({
            url: `${baseURL}/orders/notes/${orderId}`,
            method: "GET",
            success: function(response) {
                let notesContainer = $("#order-notes-container");
                notesContainer.empty();

                if (response.notes && response.notes.length > 0) {
                    response.notes.forEach((note, index) => {
                        notesContainer.append(`
                            <div class="mb-3 note-item">
                                <label for="note-${index + 1}" class="form-label">Note ${index + 1}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="note-${index + 1}" name="notes[]" value="${note.notes}" placeholder="Enter note">
                                    <span class="input-group-text">${note.created_at ? formatDate(note.created_at) : ''}</span>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    notesContainer.append(`
                        <div class="mb-3 note-item">
                            <label for="note-1" class="form-label">Note</label>
                            <input type="text" class="form-control" id="note-1" name="notes[]" placeholder="Enter note">
                        </div>
                    `);
                }

                $("#orderNotesForm input[name='order_id']").remove();
                $("#orderNotesForm").append(`<input type="hidden" name="order_id" value="${orderId}">`);
                $("#orderNotesModal").modal("show");
            },
            error: function(error) {
                console.error("Error fetching notes:", error);
                alert("An error occurred while fetching notes.");
            }
        });
    });

    $(document).off("click", "#add-order-note").on("click", "#add-order-note", function() {
        let notesContainer = $("#order-notes-container");
        let noteCount = notesContainer.children(".note-item").length + 1;

        notesContainer.append(`
            <div class="mb-3 note-item">
                <label for="note-${noteCount}" class="form-label">Note ${noteCount}</label>
                <input type="text" class="form-control" id="note-${noteCount}" name="notes[]" placeholder="Enter note">
            </div>
        `);
    });

    $("#orderNotesForm").on("submit", function(event) {
        event.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('orders.add_notes') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success("Notes added successfully!", "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(error) {
                toastr.error("An error occurred while saving notes.", "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
            }
        });
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'short', day: '2-digit' };
        return date.toLocaleDateString('en-US', options).replace(/ /g, '-');
    }
});


toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).on("click", ".verify-payment", function() {
    let paymentId = $(this).data("payment-id");
    let baseURL = "{{ URL::to('/') }}"; 

    $.ajax({
        url: `${baseURL}/orders/verify-payment/${paymentId}`,
        method: 'GET',
        success: function (response) {
            if (response.status) {
                Swal.fire({
                    title: "Payment Verified",
                    text: "Payment Status: " + response.status,
                    icon: "success",
                    confirmButtonText: "OK"
                });
            } else {
                Swal.fire({
                    title: "Verification Failed",
                    text: "Unable to verify the payment status.",
                    icon: "error",
                    confirmButtonText: "Retry"
                });
            }
        },
        error: function (error) {
            Swal.fire({
                title: "Error",
                text: "Error verifying payment. Please try again.",
                icon: "error",
                confirmButtonText: "Close"
            });
        }
    });
});



</script>