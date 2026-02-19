<?php
// aplicacion/vistas/empresa/nosotros.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../plantillas/cabecera.php';
?>

<link rel="stylesheet" href="/Tienda_ropa/publico/recursos/css/nosotros.css">

<!-- Banner de Nosotros -->
<section class="about-banner">
    <div class="about-overlay"></div>
    <div class="about-content">
        <h1>Nuestra Historia</h1>
        <p>Descubre quiénes somos y cómo transformamos la moda</p>
    </div>
</section>

<!-- Historia y Misión -->
<section class="about-section mission-section">
    <div class="container">
        <div class="section-grid">
            <div class="content-col" data-aos="fade-right">
                <span class="section-badge">StyleHub desde 2015</span>
                <h2 class="section-title">Una pasión por la moda que comenzó con un sueño</h2>
                <p class="section-text">Nacimos en un pequeño taller en el corazón de Lima, con la visión de crear prendas que combinen calidad, estilo y accesibilidad. Lo que comenzó como un emprendimiento familiar, hoy se ha convertido en una de las marcas de moda más reconocidas del país.</p>
                <p class="section-text">Nuestro compromiso con la calidad y la innovación nos ha permitido crecer y expandirnos, sin perder esa esencia y dedicación con la que confeccionamos cada pieza desde el primer día.</p>
                
                <div class="mission-values">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Nuestra Misión</h3>
                        <p>Inspirar confianza y expresión individual a través de prendas excepcionales, sostenibles y accesibles para todos.</p>
                    </div>
                    
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Nuestra Visión</h3>
                        <p>Ser la marca de moda líder que redefine la experiencia de compra, conectando tendencias con valores y sostenibilidad.</p>
                    </div>
                </div>
            </div>
            
            <div class="image-col" data-aos="fade-left">
                <div class="about-image-container">
                    <img src="../publico/recursos/imagenes/nosotros/tienda-historia.jpg" alt="Historia de StyleHub" class="about-image">
                    <div class="experience-badge">
                        <span class="years">10</span>
                        <span class="text">Años de<br>Experiencia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Valores -->
<section class="about-section values-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Qué nos define</span>
            <h2 class="section-title">Nuestros Valores</h2>
            <p class="section-subtitle">Los principios que guían cada decisión que tomamos</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card" data-aos="fade-up" data-aos-delay="100">
                <div class="value-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Sostenibilidad</h3>
                <p>Compromiso con prácticas responsables con el medio ambiente en toda nuestra cadena de producción.</p>
            </div>
            
            <div class="value-card" data-aos="fade-up" data-aos-delay="200">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Pasión</h3>
                <p>Amamos lo que hacemos y ponemos nuestro corazón en cada prenda que diseñamos.</p>
            </div>
            
            <div class="value-card" data-aos="fade-up" data-aos-delay="300">
                <div class="value-icon">
                    <i class="fas fa-gem"></i>
                </div>
                <h3>Calidad</h3>
                <p>Buscamos la excelencia en cada detalle, desde la selección de materiales hasta el acabado final.</p>
            </div>
            
            <div class="value-card" data-aos="fade-up" data-aos-delay="400">
                <div class="value-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Integridad</h3>
                <p>Transparencia y honestidad en cada paso, con nuestros clientes, proveedores y colaboradores.</p>
            </div>
        </div>
    </div>
</section>

<!-- Equipo -->
<section class="about-section team-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Las mentes creativas</span>
            <h2 class="section-title">Nuestro Equipo</h2>
            <p class="section-subtitle">Talento y pasión detrás de cada creación</p>
        </div>
        
        <div class="team-grid">
            <div class="team-card" data-aos="fade-up" data-aos-delay="100">
                <div class="team-image-container">
                    <img src="../publico/recursos/imagenes/nosotros/team-1.jpg" alt="Diseñador" class="team-image">
                    <div class="team-social">
                        <a href="#" class="team-social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="team-social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Carlos Mendoza</h3>
                    <span>Director Creativo</span>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="200">
                <div class="team-image-container">
                    <img src="../publico/recursos/imagenes/nosotros/team-2.jpg" alt="Diseñadora" class="team-image">
                    <div class="team-social">
                        <a href="#" class="team-social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="team-social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="team-info">
                    <h3>María Sánchez</h3>
                    <span>Diseñadora Principal</span>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="300">
                <div class="team-image-container">
                    <img src="../publico/recursos/imagenes/nosotros/team-3.jpg" alt="Gerente" class="team-image">
                    <div class="team-social">
                        <a href="#" class="team-social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="team-social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Luis Torres</h3>
                    <span>Gerente de Producción</span>
                </div>
            </div>
            
            <div class="team-card" data-aos="fade-up" data-aos-delay="400">
                <div class="team-image-container">
                    <img src="../publico/recursos/imagenes/nosotros/team-4.jpg" alt="Marketing" class="team-image">
                    <div class="team-social">
                        <a href="#" class="team-social-link"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="team-social-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="team-info">
                    <h3>Ana Díaz</h3>
                    <span>Directora de Marketing</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestras Tiendas -->
<section class="about-section stores-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Dónde encontrarnos</span>
            <h2 class="section-title">Nuestras Tiendas</h2>
            <p class="section-subtitle">Visítanos y vive la experiencia StyleHub</p>
        </div>
        
</section>

<!-- Testimonios -->
<section class="about-section testimonials-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Lo que dicen de nosotros</span>
            <h2 class="section-title">Testimonios</h2>
            <p class="section-subtitle">La satisfacción de nuestros clientes es nuestra mejor recompensa</p>
        </div>
        
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="../publico/recursos/js/nosotros.js"></script>

<?php require_once __DIR__ . '/../plantillas/pie.php'; ?>