<?php

namespace Tests\Feature;

use AccountTypes\Enum\InterestFrequencyEnum;
use App\Models\AccountType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\MemberTestLogger;
use Tests\TestCase;
use Throwable;

class MemberRegistrationTest extends TestCase
{
    use DatabaseMigrations;
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }
    private function validMemberData(int $accountType = 1): array
    {
        return [
            'name' => 'Ram Sharma',
            'phone' => '9812345678',
            'gender' => 'male',
            'father_name' => 'Hari Sharma',
            'mother_name' => 'Sita Sharma',
            'address' => 'Bhadrapur, Jhapa, Nepal',
            'dob_nepali' => '2055-05-15',
            'citizenship_number' => '123456789',
            'account_type' => $accountType,
            'opening_balance' => 1000,
            'legal_aid_type' => 'father',
            'legal_aid_name' => 'Hari Sharma',
            'legal_aid_relation' => 'Father',
            'legal_aid_citizenship_number' => '987654321',
        ];
    }

    private function runLoggedTest(string $test, callable $callback): void
    {
        MemberTestLogger::start($test);

        try {
            MemberTestLogger::info($test, [
                'submitted_fields' => array_keys($this->validMemberData()),
            ]);

            $callback($test);

            MemberTestLogger::passed($test, [
                'message' => 'Test completed successfully.',
            ]);
        } catch (Throwable $exception) {
            MemberTestLogger::failed($test, [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw $exception;
        }
    }

    private function submitMember(array $data)
    {
        return $this->from(route('member.create'))
            ->post(route('members.store'), $data);
    }

    private function assertMemberCreated(array $data): void
    {
        $response = $this->submitMember($data);

        MemberTestLogger::info(__FUNCTION__, [
            'response_status' => $response->getStatusCode(),
            'validation_errors' => session('errors', []),
            'success' => session('success'),
            'error' => session('error'),
        ]);

        $response->assertRedirect(route('member.create'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('members', [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'citizenship_number' => $data['citizenship_number'],
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_member_can_be_registered_with_account_type_one(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $this->assertMemberCreated($this->validMemberData(1));
        });
    }

    public function test_member_can_be_registered_with_account_type_two(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData(2);
            $data['phone'] = '9812345679';
            $data['citizenship_number'] = '123456790';

            $this->assertMemberCreated($data);
        });
    }

    public function test_required_fields_are_validated(): void
    {
        $this->runLoggedTest(__FUNCTION__, function ($test) {
            $response = $this->submitMember([]);

            $response->assertSessionHasErrors([
                'name',
                'phone',
                'gender',
                'father_name',
                'mother_name',
                'address',
                'dob_nepali',
                'citizenship_number',
                'account_type',
            ]);

            MemberTestLogger::info($test, [
                'validation_errors' => session('errors')?->all() ?? [],
            ]);
        });
    }

    public function test_invalid_gender_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['gender'] = 'unknown';

            $this->submitMember($data)
                ->assertSessionHasErrors('gender');
        });
    }

    public function test_invalid_phone_number_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['phone'] = 'abc';

            $this->submitMember($data)
                ->assertSessionHasErrors('phone');
        });
    }

    public function test_negative_opening_balance_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['opening_balance'] = -500;

            $this->submitMember($data)
                ->assertSessionHasErrors('opening_balance');
        });
    }

    public function test_invalid_account_type_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['account_type'] = 'invalid_account';

            $this->submitMember($data)
                ->assertSessionHasErrors('account_type');
        });
    }

    public function test_father_can_be_selected_as_legal_representative(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['legal_aid_type'] = 'father';
            $data['legal_aid_name'] = $data['father_name'];
            $data['legal_aid_relation'] = 'Father';

            $this->assertMemberCreated($data);
        });
    }

    public function test_mother_can_be_selected_as_legal_representative(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['phone'] = '9812345679';
            $data['citizenship_number'] = '123456790';
            $data['legal_aid_type'] = 'mother';
            $data['legal_aid_name'] = $data['mother_name'];
            $data['legal_aid_relation'] = 'Mother';

            $this->assertMemberCreated($data);
        });
    }

    public function test_other_person_can_be_legal_representative(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['phone'] = '9812345680';
            $data['citizenship_number'] = '123456791';
            $data['legal_aid_type'] = 'other';
            $data['legal_aid_name'] = 'Ram Bahadur';
            $data['legal_aid_relation'] = 'Uncle';

            $this->assertMemberCreated($data);
        });
    }

    public function test_representative_citizenship_is_required_when_applicable(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();
            $data['legal_aid_citizenship_number'] = '';

            $this->submitMember($data)
                ->assertSessionHasErrors('legal_aid_citizenship_number');
        });
    }

    public function test_profile_image_can_be_uploaded(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            Storage::fake('public');

            $data = $this->validMemberData();
            $data['image'] = UploadedFile::fake()->image('profile.jpg');

            $this->submitMember($data)
                ->assertSessionHasNoErrors();
        });
    }

    public function test_citizenship_documents_can_be_uploaded(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            Storage::fake('public');

            $data = $this->validMemberData();
            $data['citizenship_front'] = UploadedFile::fake()->image('front.jpg');
            $data['citizenship_back'] = UploadedFile::fake()->image('back.jpg');

            $this->submitMember($data)
                ->assertSessionHasNoErrors();
        });
    }

    public function test_registration_works_without_images(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $this->assertMemberCreated($this->validMemberData());
        });
    }

    public function test_invalid_profile_image_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();

            $data['image'] = UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            );

            $this->submitMember($data)
                ->assertSessionHasErrors('image');
        });
    }

    public function test_profile_image_larger_than_two_mb_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();

            $data['image'] = UploadedFile::fake()
                ->image('large.jpg')
                ->size(2049);

            $this->submitMember($data)
                ->assertSessionHasErrors('image');
        });
    }

    public function test_invalid_citizenship_document_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $data = $this->validMemberData();

            $data['citizenship_front'] = UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            );

            $this->submitMember($data)
                ->assertSessionHasErrors('citizenship_front');
        });
    }

    public function test_duplicate_citizenship_number_is_rejected(): void
    {
        $this->runLoggedTest(__FUNCTION__, function () {
            $firstMember = $this->validMemberData();

            $this->assertMemberCreated($firstMember);

            $duplicate = $this->validMemberData();
            $duplicate['name'] = 'Another Member';
            $duplicate['phone'] = '9812345681';

            $this->submitMember($duplicate)
                ->assertSessionHasErrors('citizenship_number');
        });
    }
}
