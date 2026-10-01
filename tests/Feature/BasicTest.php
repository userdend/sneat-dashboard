<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicTest extends TestCase
{
  /**
   * A basic feature test example.
   */

  public function test_homepage_returns_success(): void
  {
    $response = $this->get('/');

    $response->assertStatus(200);
  }

  public function test_homepage_contains_html(): void
  {
    $response = $this->get('/');

    $response->assertHeader('content-type', 'text/html; charset=UTF-8');
  }

  public function test_true_is_true(): void
  {
    $this->assertTrue(true);
  }

  public function test_false_is_not_true(): void
  {
    $this->assertFalse(false);
  }

  public function test_numbers_can_be_added(): void
  {
    $this->assertEquals(10, 5 + 5);
  }

  public function test_numbers_can_be_subtracted(): void
  {
    $this->assertEquals(5, 10 - 5);
  }

  public function test_numbers_can_be_multiplied(): void
  {
    $this->assertEquals(50, 10 * 5);
  }

  public function test_numbers_can_be_divided(): void
  {
    $this->assertEquals(5, 10 / 2);
  }

  public function test_string_matches_expected_value(): void
  {
    $this->assertEquals('Laravel', 'Laravel');
  }

  public function test_string_is_not_empty(): void
  {
    $this->assertNotEmpty('Laravel');
  }

  public function test_array_contains_value(): void
  {
    $this->assertContains('Laravel', [
      'Laravel',
      'Vue',
      'MySQL',
    ]);
  }

  public function test_array_has_correct_count(): void
  {
    $this->assertCount(3, [
      'Laravel',
      'Vue',
      'MySQL',
    ]);
  }

  public function test_array_has_key(): void
  {
    $data = [
      'name' => 'Laravel',
    ];

    $this->assertArrayHasKey('name', $data);
  }

  public function test_array_value_is_correct(): void
  {
    $data = [
      'name' => 'Laravel',
    ];

    $this->assertEquals('Laravel', $data['name']);
  }

  public function test_string_contains_text(): void
  {
    $this->assertStringContainsString(
      'Laravel',
      'Laravel Framework'
    );
  }

  public function test_string_starts_with_text(): void
  {
    $this->assertStringStartsWith(
      'Laravel',
      'Laravel Framework'
    );
  }

  public function test_string_ends_with_text(): void
  {
    $this->assertStringEndsWith(
      'Framework',
      'Laravel Framework'
    );
  }

  public function test_value_is_integer(): void
  {
    $this->assertIsInt(100);
  }

  public function test_value_is_string(): void
  {
    $this->assertIsString('Laravel');
  }

  public function test_value_is_array(): void
  {
    $this->assertIsArray([]);
  }
}