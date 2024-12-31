<meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <td align="center"> {{ @$order->currency_symbol.number_format(@$order->total_cost, 2) }}</td>
                    <td align="center">
                        <a href="{{ route('orders.invoice_pdf',$order->order_uuid)}}"> <i class="bi bi-receipt"></i> </a>
                        <a href="javascript:void(0);" class="add-tracking-link-btn" data-order-id="{{ $order->order_uuid }}">
                            <i class="bi bi-geo-alt"></i>
                        </a>
                        <a href="javascript:void(0);" class="add-order-note-btn" data-order-id="{{ $order->order_uuid }}">
                            <i class="bi bi-pencil-square"></i>
                        </a>

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

    document.addEventListener("DOMContentLoaded", function () {

        const trackingLinksContainer = document.getElementById("tracking-links-container");
        const addTrackingLinkBtn = document.getElementById("add-tracking-link");
        const trackingLinksForm = document.getElementById("trackingLinksForm");
        let trackingLinkCount = 1;
        let baseURL = "{{ URL::to('/') }}"; 

        addTrackingLinkBtn.addEventListener("click", function () {
            trackingLinkCount++;
            const newInput = document.createElement("div");
            newInput.classList.add("mb-3");
            newInput.innerHTML = ` 
                <label for="tracking-link-${trackingLinkCount}" class="form-label">Tracking Link</label>
                <input type="text" class="form-control" id="tracking-link-${trackingLinkCount}" name="tracking_links[]" placeholder="Enter tracking link">
            `;
            trackingLinksContainer.appendChild(newInput);
            
    });

    document.querySelectorAll(".add-tracking-link-btn").forEach(function (btn) {

        btn.addEventListener("click", function () {
            const orderId = this.getAttribute("data-order-id");

            $.ajax({
                url: `${baseURL}/orders/tracking-links/${orderId}`,
                method: 'GET',
                success: function (response) {
                    const modalBody = document.getElementById("tracking-links-container");
                    modalBody.innerHTML = '';  

                    const courierSelect = document.getElementById("courier_id");
                    if (response.courier_id) {
                        courierSelect.value = response.courier_id;
                    } else {
                        courierSelect.value = ''; 
                    }

                    if (response.tracking_links && response.tracking_links.length > 0) {
                        response.tracking_links.forEach((link, index) => {
                            const newInput = document.createElement("div");
                            newInput.classList.add("mb-3");
                            newInput.innerHTML = ` 
                                <label for="tracking-link-${index + 1}" class="form-label">Tracking Link ${index + 1}</label>
                                <input type="text" class="form-control" id="tracking-link-${index + 1}" name="tracking_links[]" value="${link}" placeholder="Enter tracking link">
                            `;
                            modalBody.appendChild(newInput);
                        });
                    } else {
                        const newInput = document.createElement("div");
                        newInput.classList.add("mb-3");
                        newInput.innerHTML = ` 
                            <label for="tracking-link-1" class="form-label">Tracking Link</label>
                            <input type="text" class="form-control" id="tracking-link-1" name="tracking_links[]" placeholder="Enter tracking link">
                        `;
                        modalBody.appendChild(newInput);
                    }

                    const hiddenOrderIdInput = document.createElement("input");
                    hiddenOrderIdInput.type = "hidden";
                    hiddenOrderIdInput.name = "order_id";
                    hiddenOrderIdInput.value = orderId;
                    trackingLinksForm.appendChild(hiddenOrderIdInput);

                    const modal = new bootstrap.Modal(document.getElementById("trackingLinksModal"));
                    modal.show();
                },
                error: function (error) {
                    console.error('Error fetching tracking links:', error);
                    alert('An error occurred while fetching tracking links.');
                }
            });
        });
        
    });

    trackingLinksForm.addEventListener("submit", function (event) {
        event.preventDefault();  

        const formData = new FormData(trackingLinksForm);

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content"); 
        formData.append('_token', CSRF_TOKEN);

        for (let pair of formData.entries()) {
            console.log(pair[0] + ": " + pair[1]);
        }

        $.ajax({
            url: '{{ route('orders.tracking_links') }}',  
            method: 'POST',
            data: formData,
            processData: false,  
            contentType: false, 
            success: function (response) {
                console.log("Server response:", response);
                toastr.success('Tracking Links added successfully!', 'Success', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
            error: function (error) {
                console.log(error);
                toastr.error('An error occurred while tracking links.', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const notesContainer = document.getElementById("order-notes-container");
    const addNoteBtn = document.getElementById("add-order-note");
    const notesForm = document.getElementById("orderNotesForm");
    let baseURL = "{{ URL::to('/') }}"; 

    document.querySelectorAll(".add-order-note-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const orderId = this.getAttribute("data-order-id");

            $.ajax({
                url: `${baseURL}/orders/notes/${orderId}`,
                method: 'GET',
                success: function (response) {
                    notesContainer.innerHTML = '';
                    if (response.notes && response.notes.length > 0) {
                        response.notes.forEach((note, index) => {
                            const noteInput = document.createElement("div");
                            noteInput.classList.add("mb-3");
                            noteInput.innerHTML = `
                                <label for="note-${index + 1}" class="form-label">Note ${index + 1}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="note-${index + 1}" name="notes[]" value="${note.notes}" placeholder="Enter note">
                                    <span class="input-group-text">${note.created_at ? formatDate(note.created_at) : ''}</span>
                                </div>
                            `;
                            notesContainer.appendChild(noteInput);
                        });
                    } else {
                        const noteInput = document.createElement("div");
                        noteInput.classList.add("mb-3");
                        noteInput.innerHTML = `
                            <label for="note-1" class="form-label">Note</label>
                            <input type="text" class="form-control" id="note-1" name="notes[]" placeholder="Enter note">
                        `;
                        notesContainer.appendChild(noteInput);
                    }

                    const hiddenOrderIdInput = document.createElement("input");
                    hiddenOrderIdInput.type = "hidden";
                    hiddenOrderIdInput.name = "order_id";
                    hiddenOrderIdInput.value = orderId;
                    notesForm.appendChild(hiddenOrderIdInput);

                    const modal = new bootstrap.Modal(document.getElementById("orderNotesModal"));
                    modal.show();
                },
                error: function (error) {
                    console.error('Error fetching notes:', error);
                    alert('An error occurred while fetching notes.');
                }
            });
        });
    });

    addNoteBtn.addEventListener("click", function () {
        const noteInput = document.createElement("div");
        noteInput.classList.add("mb-3");
        noteInput.innerHTML = `
            <label for="note" class="form-label">Note</label>
            <input type="text" class="form-control" name="notes[]" placeholder="Enter note">
        `;
        notesContainer.appendChild(noteInput);
    });

    notesForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(notesForm);

        $.ajax({
            url: '{{ route('orders.add_notes') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Server response:", response);
                toastr.success('Notes added successfully!', 'Success', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
            error: function (error) {
                console.log(error);
                toastr.error('An error occurred while saving notes.', 'Error', {
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


</script>