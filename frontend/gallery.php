<?php include 'header.php'; ?>

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5" style= "height: 350px; background-image: url(img/gallery.jpg); background-size: cover; background-position: 70% 30%;">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3  animated slideInDown" style="text-shadow: -1px 0 black, 0 2px black, 2px 0 black, 0 -1px black;">Our Gallery</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white"  href="index.php">Home</a></li>
                    <li class="breadcrumb-item text-white active"  aria-current="page">Gallery</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Gallery Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h6 class="text-primary">Campus Life</h6>
                <h1 class="mb-4">Explore NSTI Howrah Through Our Gallery</h1>
                <p class="mb-4">A visual journey through our training programs, student projects, campus facilities, and events</p>
            </div>
            <div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-12 text-center">
                    <ul class="list-inline mb-5" id="portfolio-flters">
                        <li class="mx-2 active" data-filter="*">All</li>
                        <li class="mx-2" data-filter=".training">Workshop Training & Machinery </li>
                        <li class="mx-2" data-filter=".projects">Student Projects</li>
                        <li class="mx-2" data-filter=".campus">Campus</li>
                        <li class="mx-2" data-filter=".events">Events</li>
                    </ul>
                </div>
            </div>
            <div class="row g-4 portfolio-container wow fadeInUp" data-wow-delay="0.5s">

             <!-- Campus Images -->
                <div class="col-lg-4 col-md-6 portfolio-item campus">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/campus/campus.jpg" alt="NSTI Howrah Campus" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/campus/campus.jpg" data-lightbox="portfolio" ><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Our Campus</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">View of NSTI Howrah's main building</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item campus">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/campus/ground.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/campus/ground.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Playground</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Playground</h5> -->
                    </div>
                </div>
                
                
                
                <div class="col-lg-4 col-md-6 portfolio-item campus">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/campus/commitee_room.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/campus/commitee_room.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Committee Room</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Committee room</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item campus">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/campus/lecture_hall.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/campus/lecture_hall.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Lecture Hall</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Well-equipped Lecture Hall</h5> -->
                    </div>
                </div>
              
            
            
            <!-- Training Images -->
                <div class="col-lg-4 col-md-6 portfolio-item training">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/training/carpenter.jpg" alt="Electrical Training Session" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/training/carpenter.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Practical Training Of Carpenter</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Students learning electrical wiring techniques</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item training">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/training/fitter.jpg" alt="Welding Workshop" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/training/fitter.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Practical Training Of Fitter</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Hands-on welding training under expert guidance</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item training">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/training/foundryman.jpg" alt="Welding Workshop" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/training/foundryman.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Practical Training Of Foundryman</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Hands-on welding training under expert guidance</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item training">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/training/machinist.jpg" alt="Welding Workshop" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/training/machinist.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Practical Training Of Machinist</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Hands-on welding training under expert guidance</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item training">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/training/mmv.jpg" alt="Welding Workshop" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/training/mmv.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Practical Training Of MMV</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Hands-on welding training under expert guidance</h5> -->
                    </div>
                </div>
                
                <!-- Student Projects -->
               
                
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/1.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/1.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/2.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/2.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/3.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/3.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/4.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/4.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/5.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/5.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/6.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/6.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/7.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/7.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/8.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/8.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/9.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/9.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/10.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/10.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/11.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/11.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
               
                <div class="col-lg-4 col-md-6 portfolio-item projects">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/projects/12.jpg" alt="Student Electronics Project" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/projects/12.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Students showcasing their project</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Carpenter students showcasing their project</h5> -->
                    </div>
                </div>
                               
               
                
                <!-- Events -->
               
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/vishwakarma.jpg" alt="Award Ceremony" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/vishwakarma.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Vishwakarma Puja</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Students receiving awards at annual function</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/saraswati.jpg" alt="Award Ceremony" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/saraswati.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Saraswati Puja</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Students receiving awards at annual function</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/saraswati1.jpg" alt="Award Ceremony" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/saraswati1.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Saraswati Puja</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Students receiving awards at annual function</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/womens_day.jpg" alt="Placement Drive" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/womens_day.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">womens Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Campus placement drive in progress</h5> -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/womens_day1.jpg" alt="Placement Drive" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/womens_day1.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">womens Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Campus placement drive in progress</h5> -->
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/sport.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/sport.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Sports Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Playground</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/sport1.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/sport1.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Sports Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Playground</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/sport2.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/sport2.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Sports Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Playground</h5> -->
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 portfolio-item events">
                    <div class="portfolio-img rounded overflow-hidden">
                        <img class="img-fluid" src="img/gallery/event/sport3.jpg" alt="Computer Lab" loading="lazy">
                        <div class="portfolio-btn">
                            <a class="btn btn-lg-square btn-outline-light rounded-circle mx-1" href="img/gallery/event/sport3.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="pt-3">
                        <p class="text-primary mb-0">Sports Day</p>
                        <hr class="text-primary w-25 my-2">
                        <!-- <h5 class="lh-base">Playground</h5> -->
                    </div>
                </div>
            </div>

                
            </div>
        </div>
    </div>
    <!-- Gallery End -->

    

<?php include 'footer.php'; ?>
