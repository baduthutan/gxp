// global variable
from_type = from_type ?? "airport";

$(document).ready(function () {
    $(".select2").select2({
        allowClear: true,
    });

    initData();

    $("#from_type").on("change", (e) => {
        e.preventDefault();
        if ($("#from_type").val()) {
            getFromList();
        } else {
            $("#from_master_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
            $("#from_master_sub_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
            $("#to_master_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
            $("#to_master_sub_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
        }
    });

    $("#from_master_area_id").on("change", (e) => {
        if ($("#from_master_area_id").val()) {
            console.log("a");
            let master_area_id = $("#from_master_area_id").val();
            getSubArea("#from_master_area_id", "#from_master_sub_area_id");
        } else {
            $("#from_master_sub_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
        }
    });

    $("#to_master_area_id").on("change", (e) => {
        if ($("#to_master_area_id").val()) {
            let master_area_id = $("#to_master_area_id").val();
            getSubArea("#to_master_area_id", "#to_master_sub_area_id");
        } else {
            $("#to_master_sub_area_id")
                .val(null)
                .trigger("change")
                .prop("disabled", true);
        }
    });

    $("#form_booking").on("submit", (e) => {
        e.preventDefault();
        searchSchedule();
    });
});

function initData() {
    if (
        from_type &&
        from_master_area_id &&
        to_master_area_id &&
        booking_type &&
        date_departure &&
        passenger_adult
    ) {
        $("#from_type").val(from_type);

        ajax_get_list_from_departure()
            .fail((e) => {
                console.log(e.responseText);
            })
            .done((e) => {
                let data = e.data;
                let htmlnya = '<option value=""></option>';

                data.forEach((x) => {
                    let id = x.id;
                    let name = x.name;
                    let sub_area = x.sub_area;
                    htmlnya += `<option value="${id}">${name}</option>`;
                });
                $("#from_master_area_id").html(htmlnya).prop("disabled", false);
                $("#from_master_area_id").val(from_master_area_id);

                ajax_get_list_sub_area(
                    "#from_master_area_id",
                    "#from_master_sub_area_id"
                )
                    .fail((e) => {
                        console.log(e.responseText);
                    })
                    .done((e) => {
                        let data = e.data;
                        let htmlnya = '<option value=""></option>';

                        data.forEach((x) => {
                            let id = x.id;
                            let name = x.name;
                            htmlnya += `<option value="${id}">${name}</option>`;
                        });
                        $(`#from_master_sub_area_id`)
                            .html(htmlnya)
                            .prop("disabled", false);
                        $("#from_master_sub_area_id").val(
                            from_master_sub_area_id
                        );
                    });
            });

        ajax_get_list_to_destination()
            .fail((e) => {
                console.log(e.responseText);
            })
            .done((e) => {
                let data = e.data;
                let htmlnya = '<option value=""></option>';

                data.forEach((x) => {
                    let id = x.id;
                    let name = x.name;
                    htmlnya += `<option value="${id}">${name}</option>`;
                });
                $("#to_master_area_id").html(htmlnya).prop("disabled", false);
                $("#to_master_area_id").val(to_master_area_id);

                ajax_get_list_sub_area(
                    "#to_master_area_id",
                    "#to_master_sub_area_id"
                )
                    .fail((e) => {
                        console.log(e.responseText);
                    })
                    .always((e) => {
                        $.unblockUI();
                    })
                    .done((e) => {
                        let data = e.data;
                        let htmlnya = '<option value=""></option>';

                        data.forEach((x) => {
                            let id = x.id;
                            let name = x.name;
                            htmlnya += `<option value="${id}">${name}</option>`;
                        });
                        $(`#to_master_sub_area_id`)
                            .html(htmlnya)
                            .prop("disabled", false);
                        $(`#to_master_sub_area_id`).val(to_master_sub_area_id);

                        $("#form_booking").trigger("submit");
                    });
            });
    } else {
        getFromList();
    }
}

function getFromList() {
    ajax_get_list_from_departure()
        .fail((e) => {
            console.log(e.responseText);
        })
        .done((e) => {
            console.log(e);
            let data = e.data;
            let htmlnya = '<option value=""></option>';

            data.forEach((x) => {
                let id = x.id;
                let name = x.name;
                let sub_area = x.sub_area;
                htmlnya += `<option value="${id}">${name}</option>`;
            });
            $("#from_master_area_id").html(htmlnya).prop("disabled", false);

            getToList();
        });
}

function getToList() {
    ajax_get_list_to_destination()
        .fail((e) => {
            console.log(e.responseText);
        })
        .done((e) => {
            console.log(e);
            let data = e.data;
            let htmlnya = '<option value=""></option>';

            data.forEach((x) => {
                let id = x.id;
                let name = x.name;
                htmlnya += `<option value="${id}">${name}</option>`;
            });
            $("#to_master_area_id").html(htmlnya).prop("disabled", false);
        });
}

function getSubArea(selector_parent, selector_child) {
    ajax_get_list_sub_area(selector_parent, selector_child)
        .fail((e) => {
            console.log(e.responseText);
        })
        .done((e) => {
            console.log(e);
            let data = e.data;
            let htmlnya = '<option value=""></option>';

            data.forEach((x) => {
                let id = x.id;
                let name = x.name;
                htmlnya += `<option value="${id}">${name}</option>`;
            });
            $(`${selector_child}`).html(htmlnya).prop("disabled", false);
        });
}

function ajax_get_list_from_departure() {
    return $.ajax({
        url: `/api/get_list_from_departure`,
        method: "get",
        dataType: "json",
        data: {
            from_type: $("#from_type").val(),
        },
        beforeSend: () => {
            $("#from_master_area_id")
                .html('<option value=""></option>')
                .prop("disabled", true);
            $("#from_master_sub_area_id")
                .html('<option value=""></option>')
                .prop("disabled", true);
        },
    });
}

function ajax_get_list_to_destination() {
    return $.ajax({
        url: `/api/get_list_to_destination`,
        method: "get",
        dataType: "json",
        data: {
            from_type: $("#from_type").val() == "airport" ? "city" : "airport",
        },
        beforeSend: () => {
            $("#to_master_area_id")
                .html('<option value=""></option>')
                .prop("disabled", true);
            $("#to_master_sub_area_id")
                .html('<option value=""></option>')
                .prop("disabled", true);
        },
    });
}

function ajax_get_list_sub_area(selector_parent, selector_child) {
    return $.ajax({
        url: `/api/get_list_sub_area`,
        method: "get",
        dataType: "json",
        data: {
            master_area_id: $(selector_parent).val(),
        },
        beforeSend: () => {
            $(selector_child)
                .html('<option value=""></option>')
                .prop("disabled", true);
        },
    });
}

function searchSchedule() {
    from_type = $("#from_type").val();
    date_departure = $("#date_departure").val();
    passenger_adult = $("#passenger_adult").val();
    passenger_baby = $("#passenger_baby").val();
    from_master_area_id = $("#from_master_area_id").val();
    from_master_sub_area_id = $("#from_master_sub_area_id").val();
    to_master_area_id = $("#to_master_area_id").val();
    to_master_sub_area_id = $("#to_master_sub_area_id").val();
    special_area_id = $("#special_area_id").val();

    $.ajax({
        url: `/api/get_schedule_shuttles`,
        method: "post",
        dataType: "json",
        data: {
            from_type: $("#from_type").val(),
            date_booking: $("#date_departure").val(),
            qty_adult: $("#passenger_adult").val(),
            qty_baby: $("#passenger_baby").val(),
            from_master_area_id: $("#from_master_area_id").val(),
            from_master_sub_area_id: $("#from_master_sub_area_id").val(),
            to_master_area_id: $("#to_master_area_id").val(),
            to_master_sub_area_id: $("#to_master_sub_area_id").val(),
        },
        beforeSend: () => {
            $.blockUI();
        },
    })
        .fail((e) => {
            console.log(e.responseText);
            $.unblockUI();
        })
        .done((e) => {
            console.log(e);
            $.unblockUI();
            let htmlnya = ``;
            let success = e.success;
            let message = e.message;
            let data = e.data;
            if (success == false) {
                $("#list_jadwal").html(
                    `
                    <div class="col-12 text-center">
                        <img src="img/undraw_empty_re_opql.svg" alt="not found" style="width: 400px;" />
                    </div>
                    <div class="col-12 text-center text-danger">
                        <h5 class="my-3 font-weight-bold">${message}</h5>
                    </div>
                    `
                );
            } else {
                data.forEach((el) => {
                    let id = el.id;
                    let from_type = el.from_type;
                    let from_master_area_name = el.from_master_area_name;
                    let from_master_sub_area_name =
                        el.from_master_sub_area_name ?? "";
                    let to_master_area_name = el.to_master_area_name;
                    let to_master_sub_area_name =
                        el.to_master_sub_area_name ?? "";
                    let vehicle_name = el.vehicle_name;
                    let vehicle_number = el.vehicle_number;
                    let time_departure = el.time_departure.slice(0, -3);
                    let photo = el.photo;
                    let price = el.price;
                    let driver_contact = el.driver_contact;
                    let notes = el.notes;
                    let total_seat = el.total_seat;
                    let luggage_price = el.luggage_price;
                    let total_seat_used = el.total_seat_used;
                    let is_available = el.is_available;
                    let available_seat = total_seat - total_seat_used;

                    let disabled = ``;
                    if (is_available == false) {
                        disabled = `disabled`;
                    }

                    let button_booking = /*html*/ `
                    <form method="post" action="/booking">
                        <input type="hidden" name="_token" value="${csrf}" />
                        <input type="hidden" name="from_type" value="${from_type}" />
                        <input type="hidden" name="from_master_area_id" value="${from_master_area_id}" />
                        <input type="hidden" name="from_master_sub_area_id" value="${from_master_sub_area_id}" />
                        <input type="hidden" name="to_master_area_id" value="${to_master_area_id}" />
                        <input type="hidden" name="to_master_sub_area_id" value="${to_master_sub_area_id}" />
                        <input type="hidden" name="booking_type" value="${booking_type}" />
                        <input type="hidden" name="date_departure" value="${date_departure}" />
                        <input type="hidden" name="passenger_adult" value="${passenger_adult}" />
                        <input type="hidden" name="passenger_baby" value="${passenger_baby}" />
                        <input type="hidden" name="special_area_id" value="${special_area_id}" />
                        <input type="hidden" name="schedule_id" value="${id}" />
                        <button type="submit" class="btn btn-primary ${disabled}" onclick="PreBooking(${id})">Booking</button>
                    </form>
                    `;

                    htmlnya += /*html*/ `
                        <div class="col-12 my-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 text-center">
                                            <div class="row">
                                                <div class="col-12">
                                                    <img class="img-fluid" src="/${photo}" alt="">
                                                </div>
                                                <div class="col-12 mb-4">
                                                    ${button_booking}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-8">
                                            <h5 style="font-size: 1rem; font-weight: 700;">From ${from_master_area_name} ${from_master_sub_area_name}</h5>
                                            <h5 style="font-size: 1rem; font-weight: 700;">To ${to_master_area_name} ${to_master_sub_area_name}</h5>
                                            <p class="card-text">${notes}</p>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">${vehicle_name} - ${vehicle_number}</li>
                                                <li class="list-group-item">Available Seat ${available_seat}</li>
                                                <li class="list-group-item">Rent Price $${price}</li>
                                                <li class="list-group-item">Extra Luggage Price $${luggage_price}</li>
                                                <li class="list-group-item">Time Departure ${time_departure}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $("#list_jadwal").html(htmlnya);
            }
        });
}

function PreBooking(id) {}
