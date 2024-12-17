@extends('layout.general')

@section('title')
    Chic Out
@endsection

@section('menu')
    <li>
        <a href="{{ url('ChicOut/Category/Men') }}">Men</a>
    </li>
    <li>
        <a href="{{ url('ChicOut/Category/Women') }}">Women</a>
    </li>
    <li>
        <a href="{{ url('ChicOut/Category/Kids') }}">Kids</a>
    </li>

    @auth
        @if (auth()->user()->role === 'master')
            <li><a href="{{ route('master-home') }}">Master</a></li>
        @else
            <li><a href="{{ route('cart') }}">Cart</a></li>
            <li><a href="{{ route('profile') }}">Profile</a></li>
        @endif
    @endauth
    @guest
        <li><a href="{{ route('login') }}">Login</a></li>
    @endguest
@endsection


@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container position-relative text-center text-lg-start">
            <div class="row isi-hero">
                <div class="col-lg-8">
                    <h1>Welcome to <span>ChicOut</span></h1>

                    <div class="btns">
                        <a href="{{route('category')}}" class="btn-menu animated fadeInUp scrollto">Shop Now</a>
                    </div>
                </div>

            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">

                <div class="row">
                    <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="100">
                        <div class="about-img">
                            <img src="assets/img/about.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
                        <h3 class="custom-card-title">Discover Timeless Elegance and Effortless Style with Chicout Clothing</h3> <br>
                        <p class="fst-italic">
                            Chicout Clothing offers a curated selection of fashion-forward apparel, combining quality fabrics with modern designs for a sophisticated wardrobe.
                        </p>
                        <ul>
                            <li><i class="bi bi-check-circle"></i> Chicout Clothing features a diverse collection of stylish, high-quality garments suitable for any occasion.</li>
                            <li><i class="bi bi-check-circle"></i> We focus on using premium fabrics and detailed craftsmanship to create long-lasting pieces that exude elegance.</li>
                            <li><i class="bi bi-check-circle"></i> Our designs blend classic silhouettes with contemporary trends, ensuring a perfect balance of comfort and style for every wardrobe.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

        <!-- ======= Why Us Section ======= -->
        <section id="why-us" class="why-us">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Why Us</h2>
                    <p>Why Choose Our Clothing Store</p>
                </div>

                <div class="row">

                    <div class="col-lg-4">
                        <div class="box" data-aos="zoom-in" data-aos-delay="100">
                            <span>01</span>
                            <h4>Exceptional Fashion Design</h4>
                            <p>This point highlights the unique and stylish designs of our clothing, ensuring that every piece stands out and reflects current trends.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="box" data-aos="zoom-in" data-aos-delay="200">
                            <span>02</span>
                            <h4>High-Quality Fabrics</h4>
                            <p>We are committed to using only the finest fabrics, ensuring comfort, durability, and long-lasting wear in every garment we create.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="box" data-aos="zoom-in" data-aos-delay="300">
                            <span>03</span>
                            <h4>Personalized Shopping Experience</h4>
                            <p>Our store offers a welcoming ambiance and dedicated staff who provide personalized recommendations, making your shopping experience truly special.</p>
                        </div>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Why Us Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Contact</h2>
                    <p>Contact Us</p>
                </div>
            </div>

            <div data-aos="fade-up" class="container">
                <iframe style="border:0; width: 100%; height: 350px;"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253292.47519626128!2d112.40081801279139!3d-7.275512827126228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac47f%3A0x3027a76e352be40!2sSurabaya%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1704564407594!5m2!1sid!2sid"
                    frameborder="0" allowfullscreen></iframe> <br><br>
            </div>

            <div class="container" data-aos="fade-up">
                <div class="row mt-5">
                    <div class="col-lg-4">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4 class="custom-card-title">Location:</h4>
                                <p>A108 Adam Street, New York, NY 535022</p>
                            </div>

                            <div class="open-hours">
                                <i class="bi bi-clock"></i>
                                <h4 class="custom-card-title">Open Hours:</h4>
                                <p>
                                    Monday-Saturday:<br>
                                    11:00 AM - 2300 PM
                                </p>
                            </div>

                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4 class="custom-card-title">Email:</h4>
                                <p>info@example.com</p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4 class="custom-card-title">Call:</h4>
                                <p>+1 5589 55488 55s</p>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-8 mt-5 mt-lg-0">

                        <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Subject" required>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="message" rows="8" placeholder="Message" required style="resize: none"></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>

                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main>
@endsection
