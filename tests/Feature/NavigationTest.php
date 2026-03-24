<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    /** @test */
    public function el_login_es_accesible_C201()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function el_dashboard_requiere_autenticacion_C202()
    {
        $response = $this->get('/dashboard');
        // Redirige al login si no está autenticado
        $response->assertRedirect('/login');
    }

    /** @test */
    public function la_lista_de_productos_es_accesible_C203()
    {
        $response = $this->get('/productos');
        $response->assertStatus(302); // Redirige por falta de sesión
    }
}
