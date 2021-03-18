<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DebitCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DebitCardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DebitCard $debitCard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->debitCard = DebitCard::factory()->create();
        Passport::actingAs($this->user);
    }

    public function testCustomerCanSeeAListOfDebitCards()
    {
        // get /debit-cards
        $response = $this->get('/debit-cards');
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('id')
                ->has('number')
                ->has('type')
                ->has('expiration_date')
                ->has('is_active')
        );
    }

    public function testCustomerCannotSeeAListOfDebitCardsOfOtherCustomers()
    {
        // get /debit-cards
        $response = $this->get('/debit-cards');
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->missing('id')
                ->missing('number')
                ->missing('type')
                ->missing('expiration_date')
                ->missing('is_active')
        );
    }

    public function testCustomerCanCreateADebitCard()
    {
        // post /debit-cards
        $response = $this->post('/debit-cards', ['type' => 'DEBIT']);

        $response->assertStatus(201);
    }

    public function testCustomerCanSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
        $response = $this->get('/debit-cards/', ['debitCard' => $this->debitCard]);

        $card = $this->debitCard->get($this->debitCard);

        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('id', $card->id)
                ->where('number', $card->number)
                ->where('type', $card->type)
                ->where('expiration_date', $card->expiration_date)
                ->where('is_active', $card->is_active)
                ->etc()

        );
    }

    public function testCustomerCannotSeeASingleDebitCardDetails()
    {
        // get api/debit-cards/{debitCard}
        $response = $this->get('/debit-cards/', ['debitCard' => $this->debitCard]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->missing('id')
                ->missing('number')
                ->missing('type')
                ->missing('expiration_date')
                ->missing('is_active')
                ->etc()

        );
    }

    public function testCustomerCanActivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
        $response = $this->put('/debit-cards/', ['debitCard' => $this->debitCard]);

        $response->assertStatus(202);
    }

    public function testCustomerCanDeactivateADebitCard()
    {
        // put api/debit-cards/{debitCard}
    }

    public function testCustomerCannotUpdateADebitCardWithWrongValidation()
    {
        // put api/debit-cards/{debitCard}
    }

    public function testCustomerCanDeleteADebitCard()
    {
        // delete api/debit-cards/{debitCard}
    }

    public function testCustomerCannotDeleteADebitCardWithTransaction()
    {
        // delete api/debit-cards/{debitCard}
    }

    // Extra bonus for extra tests :)
}
