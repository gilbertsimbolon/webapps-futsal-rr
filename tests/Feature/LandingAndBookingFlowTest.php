<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Field;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LandingAndBookingFlowTest extends TestCase
{
    protected $customer;
    protected $owner;
    protected $branch;
    protected $field;
    protected $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        // Cari atau buat owner
        $this->owner = User::firstOrCreate(
            ['email' => 'test_owner_venue@bkngftsl.com'],
            ['name' => 'Owner Venue Test', 'password' => bcrypt('password123'), 'status' => 'aktif']
        );
        if (!$this->owner->hasRole('pemilik')) {
            $this->owner->assignRole('pemilik');
        }

        // Cari atau buat customer
        $this->customer = User::firstOrCreate(
            ['email' => 'test_customer@bkngftsl.com'],
            ['name' => 'Customer Test', 'phone' => '08123456789', 'password' => bcrypt('password123'), 'status' => 'aktif']
        );
        if (!$this->customer->hasRole('pelanggan')) {
            $this->customer->assignRole('pelanggan');
        }

        // Cari atau buat branch
        $this->branch = Branch::firstOrCreate(
            ['slug' => 'test-branch-venue'],
            [
                'user_id'     => $this->owner->id,
                'branch_name' => 'Cabang Test Venue',
                'phone'       => '0899999999',
                'address'     => 'Jl. Lapangan Futsal No. 1',
                'description' => 'Venue futsal pengujian',
                'status'      => 'active',
            ]
        );

        // Cari atau buat payment method pemilik
        $this->paymentMethod = PaymentMethod::firstOrCreate(
            ['user_id' => $this->owner->id, 'type' => 'bank_transfer', 'name' => 'BCA Test Venue'],
            [
                'account_number' => '1234567890',
                'account_name'   => 'Owner Venue Test',
                'status'         => 'active',
                'instructions'   => 'Transfer ke BCA.',
            ]
        );

        // Cari atau buat field
        $this->field = Field::firstOrCreate(
            ['slug' => 'lapangan-test-a'],
            [
                'branch_id'      => $this->branch->id,
                'field_name'     => 'Lapangan Test A',
                'field_type'     => 'vinyl',
                'price_per_hour' => 150000,
                'status'         => 'available',
            ]
        );
    }

    public function test_landing_page_can_be_accessed_publicly()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('bkngftsl');
        $response->assertSee('Booking Lapangan');
        $response->assertSee('Jadi Lebih Mudah.');
    }

    public function test_fields_catalog_can_be_accessed_publicly()
    {
        $response = $this->get('/lapangan');
        $response->assertStatus(200);
        $response->assertSee('Daftar Lapangan Futsal');
        $response->assertSee('Lapangan Test A');
    }

    public function test_field_detail_page_can_be_accessed_publicly()
    {
        $response = $this->get('/lapangan/' . $this->field->id);
        $response->assertStatus(200);
        $response->assertSee('Lapangan Test A');
        $response->assertSee('Cabang Test Venue');
        $response->assertSee('Jadwal Tersedia');
    }

    public function test_field_slots_endpoint_returns_json()
    {
        $today = Carbon::today()->toDateString();
        $response = $this->postJson('/api/lapangan/' . $this->field->id . '/slots', [
            'date' => $today,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'date',
            'data',
        ]);
    }

    public function test_guest_cannot_access_booking_create_page_and_is_redirected_to_login()
    {
        $response = $this->get('/pelanggan/booking/' . $this->field->id);
        $response->assertRedirect('/login');
    }

    public function test_authenticated_customer_can_access_booking_create_page()
    {
        $response = $this->actingAs($this->customer)->get('/pelanggan/booking/' . $this->field->id);
        $response->assertStatus(200);
        $response->assertSee('Konfirmasi dan Pembayaran Booking');
        $response->assertSee('BCA Test Venue');
    }

    public function test_customer_booking_submission_creates_pending_booking()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $response = $this->actingAs($this->customer)->post('/pelanggan/booking', [
            'field_id'          => $this->field->id,
            'booking_date'      => $tomorrow,
            'start_time'        => '16:00',
            'duration'          => 2,
            'payment_type'      => 'full',
            'payment_method_id' => $this->paymentMethod->id,
            'notes'             => 'Tim Sparring Uji Coba',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'user_id'           => $this->customer->id,
            'field_id'          => $this->field->id,
            'booking_date'      => $tomorrow,
            'start_time'        => '16:00:00',
            'end_time'          => '18:00:00',
            'total_amount'      => 300000,
            'payment_method_id' => $this->paymentMethod->id,
            'status'            => 'pending',
        ]);
    }

    public function test_customer_can_view_riwayat_booking()
    {
        $response = $this->actingAs($this->customer)->get('/pelanggan/riwayat-booking');
        $response->assertStatus(200);
        $response->assertSee('Riwayat dan Status Booking Saya');
    }

    public function test_slots_display_all_schedules_with_three_statuses_and_team_name()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Buat schedule aktif untuk field
        \App\Models\Schedule::firstOrCreate(
            ['field_id' => $this->field->id, 'day' => 'all', 'start_time' => '10:00:00'],
            ['end_time' => '11:00:00', 'status' => 'active']
        );
        \App\Models\Schedule::firstOrCreate(
            ['field_id' => $this->field->id, 'day' => 'all', 'start_time' => '11:00:00'],
            ['end_time' => '12:00:00', 'status' => 'active']
        );

        \App\Models\Booking::where('booking_code', 'like', 'BKNG-TEST-TEAM%')->delete();

        // Buat booking terisi dengan nama tim
        \App\Models\Booking::create([
            'booking_code'      => 'BKNG-TEST-TEAM-' . uniqid(),
            'user_id'           => $this->owner->id,
            'branch_id'         => $this->branch->id,
            'field_id'          => $this->field->id,
            'booking_date'      => $tomorrow,
            'start_time'        => '10:00:00',
            'end_time'          => '11:00:00',
            'total_amount'      => 150000,
            'payment_type'      => 'full',
            'payment_method_id' => $this->paymentMethod->id,
            'status'            => 'confirmed',
            'notes'             => 'Walk-in (Rajawali FC)',
        ]);

        // Cek endpoint slots
        $response = $this->postJson('/api/lapangan/' . $this->field->id . '/slots', [
            'date' => $tomorrow,
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');

        // Cari slot 10:00
        $slot10 = collect($data)->firstWhere('start_time', '10:00');
        $this->assertNotNull($slot10);
        $this->assertEquals('terisi', $slot10['status']);
        $this->assertEquals('Rajawali FC', $slot10['team_name']);

        // Cari slot kosong berikutnya
        $slot11 = collect($data)->firstWhere('start_time', '11:00');
        $this->assertNotNull($slot11);
        $this->assertEquals('kosong', $slot11['status']);
    }

    public function test_booking_create_page_has_no_numbered_headers_and_has_date_pills_and_checkboxes()
    {
        $response = $this->actingAs($this->customer)->get('/pelanggan/booking/' . $this->field->id);
        $response->assertStatus(200);

        // Pastikan tidak ada header poin 1., 2., 3.
        $response->assertDontSee('1. Jadwal & Durasi Bermain');
        $response->assertDontSee('2. Skema Pembayaran');
        $response->assertDontSee('3. Metode Pembayaran Resmi Venue');

        // Pastikan header bersih ditampilkan
        $response->assertSee('Jadwal Bermain');
        $response->assertSee('Skema Pembayaran');
        $response->assertSee('Metode Pembayaran Resmi Venue');

        // Pastikan elemen date pills dan slot checkbox tersedia
        $response->assertSee('date-scroll-container');
        $response->assertSee('slot-check-input');
        $response->assertSee('hiddenStartTime');
        $response->assertSee('hiddenDuration');

        // Pastikan nama hari dalam Bahasa Indonesia
        $response->assertSee('Hari Ini');
        $response->assertSee('Besok');
        $response->assertDontSee('Monday');
        $response->assertDontSee('Tuesday');
        $response->assertDontSee('Wednesday');
        $response->assertDontSee('Thursday');
        $response->assertDontSee('Friday');
        $response->assertDontSee('Saturday');
        $response->assertDontSee('Sunday');
    }
}
