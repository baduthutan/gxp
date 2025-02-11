// global variable
// from_type = from_type ?? "";
// booking type init
booking_type = $("input[name=booking_type]").val();

$(document).ready(function () {
    $("#roundtrips_1").hide();
    $("#roundtrips_2").hide();
    $("#roundtrips_3").hide();

    $(".select2").select2({
        allowClear: true,
    });

    $("#date_departure").flatpickr({
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
    })

    $("#from_master_sub_area_id").on("change", function (e) {
        booking_type = $("input[name=booking_type]:checked").val();
        let master_area_id = $(this).find(":selected").data('master-area-id');
        let area_type = $(this).find(":selected").data('area-type');

        $.ajax({
            url: `/api/booking_filter/get_arrival_filter`,
            method: "post",
            dataType: "json",
            data: {
                area_type: area_type,
                booking_type: booking_type,
            },
            beforeSend: function () {
                $(`#to_master_sub_area_id`)
                    .html(`<option value=""></option>`)
                    .prop("disabled", true);
            },
            success: function (res) {
                let html = `<option value=""></option>`
                res.data.forEach((x) => {
                    html += `<optgroup label="${x.text}">`
                    x.children.forEach((y) => {
                        html += `<option value="${y.id}"  data-area-type="${y.area_type}" data-master-area-id="${y.master_area_id}">${y.name}</option>`;
                    })
                    html += `</optgroup>`
                });
                $(`#to_master_sub_area_id`)
                    .html(html)
                    .prop("disabled", false);
                $("#area_type").val(area_type);
            },
            error: function (err) {
                console.log(err.responseJSON)
            },
            complete: function () {
                //
            }
        });
    })

    $("#from_master_sub_area_id_2").on("change", function (e) {
        booking_type = $("input[name=booking_type]:checked").val();
        let master_area_id = $(this).find(":selected").data('master-area-id');
        let area_type = $(this).find(":selected").data('area-type');

        $.ajax({
            url: `/api/booking_filter/get_arrival_filter`,
            method: "post",
            dataType: "json",
            data: {
                area_type: area_type,
                booking_type: booking_type,
            },
            beforeSend: function () {
                $(`#to_master_sub_area_id_2`)
                    .html(`<option value=""></option>`)
                    .prop("disabled", true);
            },
            success: function (res) {
                let html = `<option value=""></option>`
                res.data.forEach((x) => {
                    html += `<optgroup label="${x.text}">`
                    x.children.forEach((y) => {
                        html += `<option value="${y.id}"  data-area-type="${y.area_type}" data-master-area-id="${y.master_area_id}">${y.name}</option>`;
                    })
                    html += `</optgroup>`
                });
                $(`#to_master_sub_area_id_2`)
                    .html(html)
                    .prop("disabled", false);
                $("#area_type").val(area_type);
            },
            error: function (err) {
                console.log(err.responseJSON)
            },
            complete: function () {
                //
            }
        });
    })

    $("input[name=booking_type]").on('change', function (e) {
        booking_type = $(this).val()

        console.log(booking_type)

        if (booking_type !== 'shuttle'){
            $('.passenger_adult_input').hide('fast')
            $('#passenger_adult').prop("selectedIndex", 0);
            $('#passenger_baby').prop("selectedIndex", 0);
            $('.passenger_baby_input').hide('fast')
            $('#alert-charter').show('fast')
        }else {
            $('.passenger_adult_input').show('fast')
            $('.passenger_baby_input').show('fast')
            $('#alert-charter').hide('fast')
        }

        $.ajax({
            url: `/api/booking_filter/get_departure_charter_filter`,
            method: "post",
            dataType: "json",
            data: {
                booking_type: booking_type,
            },
            beforeSend: function () {
                $(`#to_master_sub_area_id`)
                    .html(`<option value=""></option>`)
                    .prop("disabled", true);
                $(`#to_master_sub_area_id_2`)
                    .html(`<option value=""></option>`)
                    .prop("disabled", true);
            },
            success: function (res) {
                let html = `<option value=""></option>`
                res.data.forEach((x) => {
                    html += `<optgroup label="${x.text}">`
                    x.children.forEach((y) => {
                        html += `<option value="${y.id}"  data-area-type="${y.area_type}" data-master-area-id="${y.master_area_id}">${y.name}</option>`;
                    })
                    html += `</optgroup>`
                });
                $(`#from_master_sub_area_id`)
                    .html(html)
                    .prop("disabled", false);
                $(`#from_master_sub_area_id_2`)
                    .html(html)
                    .prop("disabled", false);
            },
            error: function (err) {
                console.log(err.responseJSON)
            },
            complete: function () {
                //
            }
        });
    })

    $('#passenger_adult').on('change', function (e){
        let val = $(this).val()
        $("input[name='passenger_adult']").val(val)
    })

    $('#passenger_baby').on('change', function (e){
        let val = $(this).val()

        $("input[name='passenger_baby']").val(val)
    })


    // initData();

    // $("#from_type").on("change", (e) => {
    //     e.preventDefault();
    //     if ($("#from_type").val()) {
    //         getFromList();
    //     } else {
    //         $("#from_master_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //         $("#from_master_sub_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //         $("#to_master_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //         $("#to_master_sub_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //     }
    // });

    // $("#from_master_area_id").on("change", (e) => {
    //     if ($("#from_master_area_id").val()) {
    //         console.log("a");
    //         let master_area_id = $("#from_master_area_id").val();
    //         getSubArea("#from_master_area_id", "#from_master_sub_area_id");
    //     } else {
    //         $("#from_master_sub_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //     }
    // });

    // $("#to_master_area_id").on("change", (e) => {
    //     if ($("#to_master_area_id").val()) {
    //         let master_area_id = $("#to_master_area_id").val();
    //         getSubArea("#to_master_area_id", "#to_master_sub_area_id");
    //     } else {
    //         $("#to_master_sub_area_id")
    //             .val(null)
    //             .trigger("change")
    //             .prop("disabled", true);
    //     }
    // });
    //
    // $("#form_booking").on("submit", (e) => {
    //     e.preventDefault();
    //     searchSchedule();
    // });
});

function initData() {
    if (
        // from_type &&
        from_master_area_id &&
        to_master_area_id &&
        booking_type &&
        date_departure &&
        passenger_adult
    ) {
        $("#from_type").val(from_type);

        // ajax_get_list_from_departure()
        //     .fail((e) => {
        //         console.log(e.responseText);
        //     })
        //     .done((e) => {
        //         let data = e.data;
        //         let htmlnya = '<option value=""></option>';
        //
        //         data.forEach((x) => {
        //             let id = x.id;
        //             let name = x.name;
        //             let sub_area = x.sub_area;
        //             htmlnya += `<option value="${id}">${name}</option>`;
        //         });
        //         $("#from_master_area_id").html(htmlnya).prop("disabled", false);
        //         $("#from_master_area_id").val(from_master_area_id);
        //
        //         ajax_get_list_sub_area(
        //             "#from_master_area_id",
        //             "#from_master_sub_area_id"
        //         )
        //             .fail((e) => {
        //                 console.log(e.responseText);
        //             })
        //             .done((e) => {
        //                 let data = e.data;
        //                 let htmlnya = '<option value=""></option>';
        //
        //                 data.forEach((x) => {
        //                     let id = x.id;
        //                     let name = x.name;
        //                     htmlnya += `<option value="${id}">${name}</option>`;
        //                 });
        //                 $(`#from_master_sub_area_id`)
        //                     .html(htmlnya)
        //                     .prop("disabled", false);
        //                 $("#from_master_sub_area_id").val(
        //                     from_master_sub_area_id
        //                 );
        //             });
        //     });
        //
        // ajax_get_list_to_destination()
        //     .fail((e) => {
        //         console.log(e.responseText);
        //     })
        //     .done((e) => {
        //         let data = e.data;
        //         let htmlnya = '<option value=""></option>';
        //
        //         data.forEach((x) => {
        //             let id = x.id;
        //             let name = x.name;
        //             htmlnya += `<option value="${id}">${name}</option>`;
        //         });
        //         $("#to_master_area_id").html(htmlnya).prop("disabled", false);
        //         $("#to_master_area_id").val(to_master_area_id);
        //
        //         ajax_get_list_sub_area(
        //             "#to_master_area_id",
        //             "#to_master_sub_area_id"
        //         )
        //             .fail((e) => {
        //                 console.log(e.responseText);
        //             })
        //             .always((e) => {
        //                 $.unblockUI();
        //             })
        //             .done((e) => {
        //                 let data = e.data;
        //                 let htmlnya = '<option value=""></option>';
        //
        //                 data.forEach((x) => {
        //                     let id = x.id;
        //                     let name = x.name;
        //                     htmlnya += `<option value="${id}">${name}</option>`;
        //                 });
        //                 $(`#to_master_sub_area_id`)
        //                     .html(htmlnya)
        //                     .prop("disabled", false);
        //                 $(`#to_master_sub_area_id`).val(to_master_sub_area_id);
        //
        //                 $("#form_booking").trigger("submit");
        //             });
        //     });
    } else {
        // getFromList();
    }
}

// function getFromList() {
//     ajax_get_list_from_departure()
//         .fail((e) => {
//             console.log(e.responseText);
//         })
//         .done((e) => {
//             console.log(e);
//             let data = e.data;
//             let htmlnya = '<option value=""></option>';
//
//             data.forEach((x) => {
//                 let id = x.id;
//                 let name = x.name;
//                 let sub_area = x.sub_area;
//                 htmlnya += `<option value="${id}">${name}</option>`;
//             });
//             $("#from_master_area_id").html(htmlnya).prop("disabled", false);
//
//             getToList();
//         });
// }

// function getToList() {
//     ajax_get_list_to_destination()
//         .fail((e) => {
//             console.log(e.responseText);
//         })
//         .done((e) => {
//             console.log(e);
//             let data = e.data;
//             let htmlnya = '<option value=""></option>';
//
//             data.forEach((x) => {
//                 let id = x.id;
//                 let name = x.name;
//                 htmlnya += `<option value="${id}">${name}</option>`;
//             });
//             $("#to_master_area_id").html(htmlnya).prop("disabled", false);
//         });
// }

// function getSubArea(selector_parent, selector_child) {
//     ajax_get_list_sub_area(selector_parent, selector_child)
//         .fail((e) => {
//             console.log(e.responseText);
//         })
//         .done((e) => {
//             console.log(e);
//             let data = e.data;
//             let htmlnya = '<option value=""></option>';
//
//             data.forEach((x) => {
//                 let id = x.id;
//                 let name = x.name;
//                 htmlnya += `<option value="${id}">${name}</option>`;
//             });
//             $(`${selector_child}`).html(htmlnya).prop("disabled", false);
//         });
// }
//
// function ajax_get_list_from_departure() {
//     return $.ajax({
//         url: `/api/get_list_from_departure`,
//         method: "get",
//         dataType: "json",
//         data: {
//             from_type: $("#from_type").val(),
//         },
//         beforeSend: () => {
//             $("#from_master_area_id")
//                 .html('<option value=""></option>')
//                 .prop("disabled", true);
//             $("#from_master_sub_area_id")
//                 .html('<option value=""></option>')
//                 .prop("disabled", true);
//         },
//     });
// }
//
// function ajax_get_list_to_destination() {
//     return $.ajax({
//         url: `/api/get_list_to_destination`,
//         method: "get",
//         dataType: "json",
//         data: {
//             from_type: $("#from_type").val() == "airport" ? "city" : "airport",
//         },
//         beforeSend: () => {
//             $("#to_master_area_id")
//                 .html('<option value=""></option>')
//                 .prop("disabled", true);
//             $("#to_master_sub_area_id")
//                 .html('<option value=""></option>')
//                 .prop("disabled", true);
//         },
//     });
// }
//
// function ajax_get_list_sub_area(selector_parent, selector_child) {
//     return $.ajax({
//         url: `/api/get_list_sub_area`,
//         method: "get",
//         dataType: "json",
//         data: {
//             master_area_id: $(selector_parent).val(),
//         },
//         beforeSend: () => {
//             $(selector_child)
//                 .html('<option value=""></option>')
//                 .prop("disabled", true);
//         },
//     });
// }

// function searchSchedule() {
//     from_type = $("#from_type").val();
//     date_departure = $("#date_departure").val();
//     passenger_adult = $("#passenger_adult").val();
//     passenger_baby = $("#passenger_baby").val();
//     from_master_area_id = $("#from_master_area_id").val();
//     from_master_sub_area_id = $("#from_master_sub_area_id").val();
//     to_master_area_id = $("#to_master_area_id").val();
//     to_master_sub_area_id = $("#to_master_sub_area_id").val();
//     special_area_id = $("#special_area_id").val();
//
//     $.ajax({
//         url: `/api/get_schedule_shuttles`,
//         method: "post",
//         dataType: "json",
//         data: {
//             from_type: $("#from_type").val(),
//             date_booking: $("#date_departure").val(),
//             qty_adult: $("#passenger_adult").val(),
//             qty_baby: $("#passenger_baby").val(),
//             from_master_area_id: $("#from_master_area_id").val(),
//             from_master_sub_area_id: $("#from_master_sub_area_id").val(),
//             to_master_area_id: $("#to_master_area_id").val(),
//             to_master_sub_area_id: $("#to_master_sub_area_id").val(),
//         },
//         beforeSend: () => {
//             $.blockUI();
//         },
//     })
//         .always(() => {
//             let el = document.getElementById("target_x");
//             let headerOffset = 100;
//             let elementPosition = el.getBoundingClientRect().top;
//             let offsetPosition =
//                 elementPosition + window.pageYOffset - headerOffset;
//             window.scrollTo({
//                 top: offsetPosition,
//                 behavior: "smooth",
//             });
//         })
//         .fail((e) => {
//             console.log(e.responseText);
//             $.unblockUI();
//         })
//         .done((e) => {
//             console.log(e);
//             $.unblockUI();
//             let htmlnya = ``;
//             let success = e.success;
//             let message = e.message;
//             let data = e.data;
//             if (success == false) {
//                 $("#list_jadwal").html(
//                     `
//                     <div class="col-12 text-center">
//                         <img src="{{ asset('img/undraw_empty_re_opql.svg') }}" alt="not found" style="width: 400px;" />
//                     </div>
//                     <div class="col-12 text-center text-danger">
//                         <h5 class="my-3 font-weight-bold">${message}</h5>
//                     </div>
//                     `
//                 );
//             } else {
//                 data.forEach((el) => {
//                     let id = el.id;
//                     let from_type = el.from_type;
//                     let from_master_area_name = el.from_master_area_name;
//                     let from_master_sub_area_name =
//                         el.from_master_sub_area_name ?? "";
//                     let to_master_area_name = el.to_master_area_name;
//                     let to_master_sub_area_name =
//                         el.to_master_sub_area_name ?? "";
//                     let vehicle_name = el.vehicle_name;
//                     let vehicle_number = el.vehicle_number;
//                     let time_departure = el.time_departure.slice(0, -3);
//                     let photo = el.photo;
//                     let price = el.price;
//                     let driver_contact = el.driver_contact;
//                     let notes = el.notes;
//                     let total_seat = el.total_seat;
//                     let luggage_price = el.luggage_price;
//                     let total_seat_used = el.total_seat_used;
//                     let is_available = el.is_available;
//                     let available_seat = total_seat - total_seat_used;
//
//                     let disabled = ``;
//                     if (is_available == false) {
//                         disabled = `disabled`;
//                     }
//
//                     let button_booking = /*html*/ `
//                     <form method="post" action="/booking">
//                         <input type="hidden" name="_token" value="${csrf}" />
//                         <input type="hidden" name="from_type" value="${from_type}" />
//                         <input type="hidden" name="from_master_area_id" value="${from_master_area_id}" />
//                         <input type="hidden" name="from_master_sub_area_id" value="${from_master_sub_area_id}" />
//                         <input type="hidden" name="to_master_area_id" value="${to_master_area_id}" />
//                         <input type="hidden" name="to_master_sub_area_id" value="${to_master_sub_area_id}" />
//                         <input type="hidden" name="booking_type" value="${booking_type}" />
//                         <input type="hidden" name="date_departure" value="${date_departure}" />
//                         <input type="hidden" name="passenger_adult" value="${passenger_adult}" />
//                         <input type="hidden" name="passenger_baby" value="${passenger_baby}" />
//                         <input type="hidden" name="special_area_id" value="${special_area_id}" />
//                         <input type="hidden" name="schedule_id" value="${id}" />
//                         <button type="submit" class="btn text-white bg-success h-100 fs-14 shadow br-button text-uppercase ${disabled}" onclick="PreBooking(${id})">Booking</button>
//                     </form>
//                     `;
//
//                     htmlnya += /*html*/ `
//                         <div class="card mb-3 shadow1 radius1 border-0">
//                             <div class="card-body shadow">
//                                 <div class="row mx-0">
//                                     <div class="col-12 col-lg-8 px-lg-0">
//                                         <div class="d-table w-100">
//                                             <div class="d-table-row">
//                                                 <div class="d-table-cell align-middle">
//                                                     <p class="disable-select pb-sm-0 mb-sm-0" style="padding-right:18px;"><img
//                                                             class="disable-select" draggable="false" src="/img/route.png"
//                                                             style="width:4px; height:35px;"> </p>
//                                                 </div>
//                                                 <div class="d-table-cell align-middle pr-5">
//                                                     <p class="mb-2 font-weight-bold text-muted">${from_master_area_name} ${from_master_sub_area_name}
//                                                     </p>
//                                                     <p class="mb-0 font-weight-bold text-muted">${to_master_area_name} ${to_master_sub_area_name}</p>
//                                                 </div>
//                                                 <div class="d-table-cell align-middle pl-5">
//                                                     <p
//                                                         class="align-middle text-right text-lg-center font-weight-bold pb-0 mb-0 font-weight-bold">
//                                                         Time Departure
//                                                         <span class="text-danger font-weight-bold">${time_departure}</span>
//                                                     </p>
//                                                 </div>
//                                             </div>
//                                         </div>
//                                     </div>
//
//                                     <div class="col-4 col-lg-2 my-auto">
//                                         <div class="d-table mt-3 mt-lg-0">
//                                             <div class="d-table-row">
//                                                 <div class="d-table-cell align-middle">
//                                                     <p class="pb-0 mb-0 text-muted"><span
//                                                             class="text-danger font-weight-bold">${available_seat}</span>
//                                                         seat</p>
//                                                 </div>
//                                             </div>
//                                         </div>
//                                     </div>
//
//                                     <div class="col-8 col-lg-2 px-lg-0 my-auto">
//                                         <div class="d-table ml-auto mt-3 mt-lg-0">
//                                             <div class="d-table-row">
//                                                 <div class="d-table-cell">
//                                                     <p class="font-weight-bold pb-0 mb-0">$${price}</p>
//                                                 </div>
//                                                 <div class="d-table-cell pl-3">
//                                                     ${button_booking}
//                                                 </div>
//                                             </div>
//                                         </div>
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     `;
//                 });
//
//                 $("#list_jadwal").html(htmlnya);
//             }
//         });
// }

function PreBooking(id) {

}
