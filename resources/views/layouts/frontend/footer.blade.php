<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="footer-col">
                    <h3>About Us</h3>
                    <p>Green express, LCC</p>
                    <p>A licensed, Insured and Trusted Transportation Company.</p>
                    <p>US DOT 2839427</p>
                    <p>MC 950729</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="footer-col">
                    <h3>Pages</h3>
                    <ul>
                        <li>
                            <a href="/services">Services</a>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <a href="/schedule">Schedule and Location</a>
                        </li>
                    </ul>
                    @foreach ($pages as $page)
                    @if (!in_array($page->id, [2,4,5,6]))
                        <ul>
                            <li>
                                <a href="/page/{{ $page->slug }}">{{ $page->page_title }}</a>
                            </li>
                        </ul>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="footer-col">
<!--                    <h3>Social Pages</h3>-->
<!--                    <ul>-->
<!--                        <li>-->
<!--                            <a href="https://web.facebook.com" target="_blank">Facebook</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="https://instagram.com" target="_blank">Instagram</a>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <a href="https://telegram.com" target="_blank">Telegram</a>-->
<!--                        </li>-->
<!--                    </ul>-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-white">
                <p class="copyright-text">
                <div class="float-right d-none d-sm-block">
<!--                    Version 1.0.1-->
                </div>
                <strong>Copyright &copy; 2022 <a href=" #" class="footer_link">{{ $app_name }}</a></strong>
                </p>
            </div>
        </div>
    </div>
</footer>
