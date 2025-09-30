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
                            <img src="imagenes/hero/redes.webp" alt="">
                            <a href="#">Ver mas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accesos">
        <a href="https://carpin-corp.monday.com/?slug=carpin-corp" target="_blank">
            <img class="logos" src="imagenes/logomonday.webp" alt="Monday">
        </a>
        <a href="https://desarrolladoracarpin.bizneohr.com/" target="_blank">
            <img class="logos" src="imagenes/bizneo.jpg" alt="Bizneo">
        </a>
        <a href="https://spa.checklistfacil.com.br/login?lang=es-es" target="_blank">
            <img class="logos" src="imagenes/checklist.jpeg" alt="Checklist">
        </a>
    </div>
    

    <!-- Cards de capacitaciones, eventos y proximos 
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
    -->

    
</x-app-layout>
