<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\User;

class RoleMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_has_role_enum_and_no_is_admin_column()
    {
        $this->assertTrue(Schema::hasColumn('users', 'role'));
        $this->assertFalse(Schema::hasColumn('users', 'is_admin'));
    }

    /** @test */
    public function user_role_default_value_is_user()
    {
        $user = User::factory()->create();
        $this->assertEquals('user', $user->role);
    }

    /** @test */
    public function is_admin_helper_method_works_correctly()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($superadmin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function is_super_admin_helper_method_works_correctly()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertTrue($superadmin->isSuperAdmin());
    }
}
