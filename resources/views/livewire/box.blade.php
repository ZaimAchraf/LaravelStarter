<section id="portfolio-details" class="portfolio-details">
    <div class="container">

        <div class="row gy-4">

            <div class="col-lg-8">
                <div class="portfolio-details-slider swiper">
                    <div class="swiper-wrapper align-items-center">
                        @foreach($box->pictures as $picture)
                            <div class="swiper-slide">
                                <img src="{{asset('uploads')}}/boxes/{{ $picture->url}}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="portfolio-info">
                    <h3>{{$box->title}}</h3>
                    <ul>
                        <li><strong>Categorie</strong>: {{$box->series->get(0)->title}}</li>
                        <li><strong>Theme</strong>: {{$box->series->get(0)->theme}}</li>
                        <li><strong>Prix unitaire</strong>: {{$box->price}} MAD</li>
                        @if($isModal)
                            <li><strong>Plus de details</strong>: <a  href="/ecommerce/box/{{$box->id}}/0">Details</a></li>
                        @endif
                    </ul>
                </div>
                <div class="portfolio-description">
                    <h2>{{$box->description}}</h2>
                </div>
            </div>

        </div>

    </div>
</section>
