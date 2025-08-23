<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="hero">
        <div class="content">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="card">
                            <img src="imagenes/hero/revista-hero.jpg" alt="">
                            <a href="{{ route('picoteando') }}">Ver mas</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card">
                            <img src="imagenes/hero/expo.jpg" alt="">
                            <a href="#">Ver mas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="body">
        <div class="card">
            <img src="imagenes/train.jpg" alt="">
           <a href="{{ route('capacitaciones') }}"><p>Capacitaciones</p></a>
        </div>
        <div class="card">
            <img src="imagenes/event.jpeg" alt="">
            <a href="#"><p>Eventos</p></a>
        </div>
        <div class="card">
            <img src="imagenes/train.jpg" alt="">
            <a href="#"><p>Proximos</p></a>
        </div>
    </div>


    
</x-app-layout>
