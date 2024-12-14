@extends('layouts.frontend.app')
@section('page_content')
<section id="home" class="home d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 my-3 text-center">
                <h1 class="text-center mt-5">Booking Number - {{ $bookings->booking_number }}</h1>
                <span class="text-muted font-italic">
            <small>
                Please save and keep this number for <b>"Check Booking"</b> ID
              </small>
              </span>
            </div>

            <div class="col-12 mb-5">
                <div class="card bg-light shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <h3>Process the Payment</h3>
                                <div class="alert alert-warning" role="alert">
                                    <span class="font-weight-bold">Accept Visa, Mastercard, American Express, Discover</span>
                                    payments from customers worldwide.
                                </div>
                                <form role="form" action="{{ route('booking.process') }}" method="post"
                                      class="validation form-row"
                                      data-cc-on-file="false"
                                      data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                                      id="payment-form">

                                        @csrf
                                        <input type="hidden" name="hcode" value="{{ $hcode }}">
                                        <input type="hidden" name="intent_id" value="{{ $intent_id }}">

                                        <div id="stripe-element" style="width: 100%;">

                                        </div>

                                        @if (session('message'))
                                            {!! session('message') !!}
                                        @endif

                                        <div class='form-row row'>
                                            <div class='col-md-12 d-none error' style="width: 100%;">
                                                <div class="alert alert-danger" role="alert">
                                                    Fix the errors before you begin.
                                                </div>
                                            </div>
                                        </div>


                                        <button class="btn btn-primary btn-lg btn-block mt-2 btn-submit" type="submit">Pay Now
                                            ($ {{$bookings->total_price}})
                                        </button>

                                    </form>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <h3>Price Detail</h3>
                                    <table class="table table-bordered bg-dark text-white">
                                        <tbody>
                                        <tr>
                                            <td>
                                                Base Price:<br/>
                                                @php
                                                    $passanger_total = $bookings->qty_adult + $bookings->qty_baby;
                                                @endphp
                                                {{ $passanger_total }} Passenger(s)
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->total_base_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Special request drop off Price:<br/>
                                                {{ $bookings->regional_name }}
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->extra_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Luggage Price:<br/>
                                                {{ $bookings->luggage_qty ?? '0' }} piece(s)
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->luggage_price ?? '0' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Voucher:<br/>
                                                {{ $vouchers->code ?? '' }}  {{-- adding condition when user doesn't input voucher when creating booking --}}
                                            </td>
                                            <td class="text-right text-warning font-weight-bold">
                                                ${{ $bookings->promo_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Sub Total:
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->sub_total_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Service Fee (3.7%):
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->fee_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">
                                                Grand Total:
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                ${{ $bookings->total_price }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vitamin')
    {{-- manipulate dom and stripe logic   --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function () {
            const stripe = Stripe("{{ env("STRIPE_KEY") }}");

            let elements;

            initialize();

            document
                .querySelector("#payment-form")
                .addEventListener("submit", handleSubmit);

            function initialize() {
                elements = stripe.elements({clientSecret: "{{ $client_secret }}"});

                const paymentElement = elements.create("payment");
                paymentElement.mount("#stripe-element");
            }

            async function handleSubmit(e) {
                e.preventDefault();
                $('.btn-submit').addClass('disabled')
                const {setupIntent, error} = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        // Make sure to change this to your payment completion page
                        // return_url: "https://lolucant.com/public/checkout.html",
                    },
                    redirect: "if_required"
                });

                if (error) {
                    if (error.type === "card_error" || error.type === "validation_error") {
                       showMessage(error.message);
                    } else {
                       showMessage("Something wrong with this transaction.");
                    }
                } else {
                    let form = document.getElementById('payment-form')
                    form.submit()

                }

                $('.btn-submit').removeClass('disabled')

            }

            function showMessage(messageText) {
                const messageContainer = document.querySelector(".error");

                messageContainer.classList.remove("d-none");
                messageContainer.innerHTML = `<div class="alert alert-danger" role="alert">
                                                    ${messageText}
                                                </div>`;

                setTimeout(function () {
                    messageContainer.classList.add("d-none");
                    messageText.innerHTML = "";
                }, 4000);
            }


        })
    </script>
@endsection
