<?php

namespace Tests\Feature;

use App\Models\Salesman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesmanApiTest extends TestCase
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

    /**
     * A basic test example.
     */
    public function test_create_salesman(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johndoe@example.com', 'password');

        $response = $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = $response->getContent();
        $this->assertJson($createdSalesman);

        $createdSalesman = json_decode($createdSalesman);

        $this->assertEquals('Jozef', $createdSalesman->first_name);
        $this->assertEquals('Senec', $createdSalesman->last_name);
        $this->assertEquals('44553', $createdSalesman->prosight_id);
        $this->assertEquals('jozefsenec@ba.sk', $createdSalesman->email);
        $this->assertEquals('m', $createdSalesman->gender);
        $this->assertEquals('married', $createdSalesman->marital_status);

        $response->assertStatus(201);
    }

    public function test_do_not_create_data_out_of_range(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $response = $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Boris', 'last_name' => 'Blazej', 'prosight_id' => '55555555', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(416);
        $response->assertContent('{"message":"Input data out of range. Field prosight_id of value 55555555 is out of range. Acceptable range for this field is 5."}');
    }

    public function test_do_not_create_input_bad_format(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $response = $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'mail',
                'gender'     => 'www', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(400);
        $response->assertContent('{"message":"Bad format of input data. Fields: The email field must be a valid email address. The gender field is required."}');
    }

    public function test_do_not_create_salesman_already_exists(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response = $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(409);
        $response->assertContent('{"message":"Couldn\'t create a salesman. Salesman with Prosight ID: 44553 already exists."}');
    }

    public function test_update_salesman()
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johndoe@example.com', 'password');

        $response = $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(201);

        $createdSalesman = Salesman::query()->first();

        $this->assertEquals('Jozef', $createdSalesman->getAttribute('first_name'));
        $this->assertEquals('Senec', $createdSalesman->getAttribute('last_name'));
        $this->assertEquals('m', $createdSalesman->getAttribute('gender'));
        $this->assertEquals('married', $createdSalesman->getAttribute('marital_status'));

        $response = $this->putJson('http://localhost/api/salesman',
            [
                'uuid'      => $createdSalesman->getAttribute('uuid'), 'first_name' => 'Jozefína',
                'last_name' => 'Senecká', 'gender' => 'žena', 'marital_status' => 'rozvedená'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman->refresh();

        $this->assertEquals('Jozefína', $createdSalesman->getAttribute('first_name'));
        $this->assertEquals('Senecká', $createdSalesman->getAttribute('last_name'));
        $this->assertEquals('f', $createdSalesman->getAttribute('gender'));
        $this->assertEquals('divorced', $createdSalesman->getAttribute('marital_status'));

        $response->assertStatus(200);
    }

    public function test_do_not_update_salesman_not_found()
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johndoe@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = Salesman::query()->first();

        $this->assertNotNull($createdSalesman);

        $response = $this->putJson('http://localhost/api/salesman',
            [
                'uuid' => 'e64dada8-d24d-421d-9a66-ca93df60bf97', 'first_name' => 'Jozefína'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(404);
        $response->assertContent('{"message":"Salesman e64dada8-d24d-421d-9a66-ca93df60bf97 not found."}');
    }

    public function test_do_not_update_input_bad_format(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = Salesman::query()->first();

        $this->assertNotNull($createdSalesman);

        $response = $this->putJson('http://localhost/api/salesman',
            [
                'uuid' => $createdSalesman->getAttribute('uuid'), 'first_name' => null, 'last_name' => null
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(400);
        $response->assertContent('{"message":"Bad format of input data. Fields: The first name field must be a string. The last name field must be a string."}');
    }

    public function test_do_not_update_data_out_of_range(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = Salesman::query()->first();

        $this->assertNotNull($createdSalesman);

        $response = $this->putJson('http://localhost/api/salesman',
            [
                'uuid' => $createdSalesman->getAttribute('uuid'), 'prosight_id' => '77'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(416);
        $response->assertContent('{"message":"Input data out of range. Field prosight_id of value 77 is out of range. Acceptable range for this field is 5."}');
    }

    public function test_delete_salesman()
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = Salesman::query()->first();
        $createdSalesmanUuid = $createdSalesman->getAttribute('uuid');

        $this->assertNotNull($createdSalesman);

        $this->deleteJson('http://localhost/api/salesman',
            [
                'uuid' => $createdSalesmanUuid
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $this->assertNull(Salesman::query()->where('uuid', $createdSalesmanUuid)->first());
    }

    public function test_do_not_delete_input_bad_format(): void
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $createdSalesman = Salesman::query()->first();

        $this->assertNotNull($createdSalesman);

        $response = $this->deleteJson('http://localhost/api/salesman',
            [
                'uuid' => 'bbbbbbbbb-a'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(400);
        $response->assertContent('{"message":"Bad format of input data. Fields: The uuid field must be a valid UUID."}');
    }

    public function test_do_not_delete_salesman_not_found()
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $response = $this->deleteJson('http://localhost/api/salesman',
            [
                'uuid' => 'e64dada8-d24d-421d-9a66-ca93df60bf97'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $response->assertStatus(404);
        $response->assertContent('{"message":"Salesman e64dada8-d24d-421d-9a66-ca93df60bf97 not found."}');
    }

    public function test_salesman_list()
    {
        $this->logoutUsers();
        $token = $this->loginUserGetToken('johnwick@example.com', 'password');

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Jozef', 'last_name' => 'Senec', 'prosight_id' => '44553', 'email' => 'jozefsenec@ba.sk',
                'gender'     => 'muž', 'marital_status' => 'ženatý'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $this->postJson('http://localhost/api/salesman',
            [
                'first_name' => 'Michal', 'last_name' => 'Vana', 'prosight_id' => '11223', 'email' => 'michalvana@v.sk',
                'gender' => 'muž', 'marital_status' => 'rozvedený'
            ],
            ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json']
        );

        $this->assertCount(2, Salesman::query()->get());

        $response = $this->get('http://localhost/api/salesman?page=1&per_page=10&sort=-last_name');
        $this->assertJson($response->getContent());
        $salesmanResponse = json_decode($response->getContent(), true);

        $salesmanCollection = collect($salesmanResponse['data']);
        $this->assertCount(2, $salesmanCollection);

        $this->assertArrayHasKey('links', $salesmanResponse);

        // test order
        $this->assertEquals('Vana', $salesmanCollection->first()['last_name']);
    }

    public function test_salesman_list_input_bad_format()
    {
        $this->logoutUsers();
        $this->loginUserGetToken('johnwick@example.com', 'password');

        $response = $this->get('http://localhost/api/salesman?a=1&b=10&sort=-c');

        $response->assertContent('{"message":"Bad format of input data. Fields: The page field is required. The per page field is required. The selected sort is invalid."}');
        $response->assertStatus(400);
    }
}
