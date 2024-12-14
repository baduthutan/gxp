@extends('layouts.frontend.app')
@section('page_content')
<section id="home" class="home d-flex align-items-center" data-scroll-index="0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div id="slick_slider" class="shadow" style="margin-top: 0px;">
                    <div>
                    <img src="img/slider/1674635582.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                    <div>
                    <img src="img/slider/1674635593.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                    <div>
                    <img src="img/slider/1674635602.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                </div>           
            </div>
        </div>
    </div>
</section>

    <section id="booking" class="booking section-padding" data-scroll-index="1">
<!--        <img src="img/8493.jpg" class="bg" loading="lazy" />-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 mb-5">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="text-center font-weight-bold mb-4">Airport Shuttle & Charter Booking</h5>
                            <form action="{{ route('search') }}" method="get">
                                <div class="form-group">
                                    <label for="from_type" class="form-text font-weight-bold">From Type</label>
                                    <select class="form-control select2" id="from_type" name="from_type"
                                        data-placeholder="From Type" required>
                                        <option value="airport">Airport</option>
                                        <option value="city">City</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="from_master_area_id"
                                        class="form-text font-weight-bold">From/Departure</label>
                                    <select class="form-control select2" id="from_master_area_id" name="from_master_area_id"
                                        data-placeholder="From/Departure" required disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="from_master_sub_area_id" class="form-text font-weight-bold">Sub
                                        From/Departure</label>
                                    <select class="form-control select2" id="from_master_sub_area_id"
                                        name="from_master_sub_area_id"
                                        data-placeholder="Sub
                                        From/Departure"
                                        disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="to_master_area_id" class="form-text font-weight-bold">To/Arrival</label>
                                    <select class="form-control select2" id="to_master_area_id" name="to_master_area_id"
                                        data-placeholder="To/Arrival" required disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="to_master_sub_area_id" class="form-text font-weight-bold">Sub
                                        To/Arrival</label>
                                    <select class="form-control select2" id="to_master_sub_area_id"
                                        name="to_master_sub_area_id"
                                        data-placeholder="Sub
                                        To/Arrival" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="shuttle"
                                            value="shuttle" checked>
                                        <label class="form-check-label" for="shuttle">Shuttle</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="charter"
                                            value="charter">
                                        <label class="form-check-label" for="charter">Charter</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_departure" class="form-text font-weight-bold">Outward
                                        journey</label>
                                    <input type="date" class="form-control" id="date_departure" name="date_departure"
                                        placeholder="Outward Journey" required />
                                </div>
                                <div class="form-group">
                                    <label for="passanger_adult" class="form-text font-weight-bold">Adult
                                        Passangers</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="passanger_adult"
                                            name="passanger_adult" placeholder="Adult Passangers" min="1"
                                            max="9" value="1" required />
                                        <div class="input-group-append bg-light">
                                            <span class="input-group-text">Adult</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="passanger_baby" class="form-text font-weight-bold">Child
                                        Passangers</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="passanger_baby"
                                            name="passanger_baby" placeholder="Adult Passangers" min="0"
                                            max="9" value="0" required />
                                        <div class="input-group-append bg-light">
                                            <span class="input-group-text">Child</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Child is under 8 years old
                                    </small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search fa-fw"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="card card-semi shadow">
                        <div class="card-body">
                            <div class="section-title">
                                <h1>A licensed, Insured and Trusted Transportation Company.</h1>
                            </div>
                            <div class="booking-text">
                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Praesentium mollitia eum
                                    ducimus
                                    accusamus, nisi repellat distinctio iusto deleniti, saepe, nobis ex reprehenderit?
                                    Pariatur
                                    cupiditate consequatur unde exercitationem, sit a adipisci molestias labore,
                                    officiis
                                    repudiandae esse voluptate eveniet magni laborum vel. Labore magni modi sit fugiat
                                    neque!
                                    Quo eligendi corporis in, repudiandae molestias dignissimos eum recusandae officiis,
                                    atque
                                    incidunt hic? Optio possimus ut laudantium accusamus reprehenderit dolorem mollitia
                                    sed
                                    aliquam explicabo corrupti? Quisquam nisi vitae, quod quibusdam libero quas quidem
                                    molestiae. Aspernatur ad fuga itaque. Amet enim recusandae blanditiis aliquid
                                    tenetur natus
                                    fugiat, distinctio quae! Sit sapiente a corrupti praesentium ea in ipsam cupiditate
                                    dolor
                                    atque, at saepe soluta reiciendis vel qui eligendi dignissimos repudiandae nemo
                                    ipsa, modi,
                                    exercitationem ullam quasi? Architecto similique, quasi, tempore placeat, laudantium
                                    minus
                                    sapiente vel laborum tempora fuga eligendi alias velit qui maxime molestiae labore
                                    omnis
                                    quia! Alias corporis pariatur quae minima? Iste, ex tempore nulla perferendis nisi
                                    explicabo
                                    incidunt illo aliquam accusantium unde dolorem possimus harum est ipsam tenetur
                                    corporis
                                    neque, magnam laudantium obcaecati atque suscipit. Ea obcaecati cumque deserunt
                                    alias
                                    officiis, esse ipsum quibusdam accusantium suscipit nihil saepe aliquam eaque maxime
                                    veniam
                                    magni minus soluta ullam aliquid? Iure aperiam, itaque dolores consectetur qui
                                    saepe.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="profile" class="profile section-padding" data-scroll-index="2">
        <div class="container">
            <div class="section-title">
                <h1>Our Services</h1>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card card-shadow">
                        <img src="https://greenexpressone.com/wp-content/uploads/2022/04/Picture2.png"
                            class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Whether you’re a business traveller, a couple, a big group of friends
                                or family
                                travelling with lots of luggage, by reserving your airport shuttle or private car you
                                can have the reassurance and security that you’ll be picked up on time and taken
                                straight to your home, hotel or attraction</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-shadow">
                        <img src="https://greenexpressone.com/wp-content/uploads/2022/04/green.jpg" class="card-img-top"
                            alt="">
                        <div class="card-body">
                            <p class="card-text">We drive safely and follow all rules of the road to ensure you have a
                                safe and
                                pleasurable trip</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-shadow">
                        <img src="https://greenexpressone.com/wp-content/uploads/2022/04/istockphoto-1206670377-640x640-1-300x169.jpg"
                            class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Passenger Pick Up And Drop Off Services</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vitamin')
    <script>
        let booking_type = null;
    </script>
    <script src="js/booking.js"></script>
@endsection
