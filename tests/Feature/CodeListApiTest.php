<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeListApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUsers();
    }

    private function createUsers()
    {
        User::query()->create([
            'name'     => 'John Doe',
            'email'    => 'johndoe@example.com',
            'password' => bcrypt('password'),
        ]);

        User::query()->create([
            'name'     => 'John Wick',
            'email'    => 'johnwick@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    private function loginUserGetToken(string $email, string $password): string
    {
        $this->assertCount(2, User::query()->get());
        $response = $this->post('http://localhost/api/login',
            ['email' => $email, 'password' => $password]
        );
        $responseData = $response->getContent();
        $this->assertJson($responseData);

        $user = json_decode($responseData, false);
        $this->assertNotNull($user->token);

        return $user->token;
    }

    private function logoutUsers()
    {
        foreach (User::query()->cursor() as $user) {
            $response = $this->post('http://localhost/api/logout',
                ['user_id' => $user->id]
            );

            $response->assertStatus(200);
        }
    }

    public function test_get_code_list()
    {
        $this->logoutUsers();
        $this->loginUserGetToken('johnwick@example.com', 'password');

        $response = $this->get('http://localhost/api/codelists');
        $this->assertJson($response->getContent());
        $codeList = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('marital_statuses', $codeList);
        $this->assertArrayHasKey('genders', $codeList);
        $this->assertArrayHasKey('titles_before', $codeList);
        $this->assertArrayHasKey('titles_after', $codeList);
    }
}
