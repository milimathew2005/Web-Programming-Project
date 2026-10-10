<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that user registration creates student users only with auto student_id.
     */
    public function test_registration_creates_student_only(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Student',
            'email' => 'john@student.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/books');

        $user = User::where('email', 'john@student.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('student', $user->role);
        $this->assertNotNull($user->student_id);
    }

    /**
     * Test that librarian login redirects to /librarian/dashboard.
     */
    public function test_librarian_login_redirects_to_librarian_dashboard(): void
    {
        $librarian = User::factory()->librarian()->create([
            'email' => 'librarian@test.com',
        ]);

        $response = $this->post('/login', [
            'email' => 'librarian@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($librarian);
        $response->assertRedirect('/librarian/dashboard');
    }

    /**
     * Test that student login redirects to /books catalog.
     */
    public function test_student_login_redirects_to_books_catalog(): void
    {
        $student = User::factory()->create([
            'email' => 'student@test.com',
        ]);

        $response = $this->post('/login', [
            'email' => 'student@test.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($student);
        $response->assertRedirect('/books');
    }

    /**
     * Test that student users receive 403 Forbidden on librarian routes.
     */
    public function test_student_receives_403_on_librarian_routes(): void
    {
        $student = User::factory()->create();

        $librarianRoutes = [
            '/librarian/dashboard',
            '/librarian/books',
            '/librarian/reservations',
            '/librarian/loans',
            '/librarian/fines',
        ];

        foreach ($librarianRoutes as $route) {
            $response = $this->actingAs($student)->get($route);
            $response->assertStatus(403);
        }
    }

    /**
     * Test that librarian users receive 403 Forbidden on student routes.
     */
    public function test_librarian_receives_403_on_student_routes(): void
    {
        $librarian = User::factory()->librarian()->create();

        $studentRoutes = [
            '/books',
            '/student/reservations',
            '/student/fines',
        ];

        foreach ($studentRoutes as $route) {
            $response = $this->actingAs($librarian)->get($route);
            $response->assertStatus(403);
        }
    }

    /**
     * Test that unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/librarian/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/books');
        $response->assertRedirect('/login');
    }
}
