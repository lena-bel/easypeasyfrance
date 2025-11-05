<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class AdminDashboardTest extends WebTestCase
{
    public function testAdminDashboardLoadsSuccessfully(): void
    {
        // Create a fake browser client
        $client = static::createClient();

        // Get the user repository to fetch our test admin user
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Find the admin created by your fixtures (AdminUserFixtures)
        $testAdmin = $userRepository->findOneByEmail('admin@easypeasyfrance.fr');

        // Make sure the user exists
        $this->assertNotNull($testAdmin, 'Admin user not found. Did you load fixtures with doctrine:fixtures:load --env=test ?');

        // Simulate admin login
        $client->loginUser($testAdmin);

        // Visit the admin dashboard route (adjust path if different)
        $crawler = $client->request('GET', '/admin/');

        // Ensure the response is successful (HTTP 200)
        $this->assertResponseIsSuccessful();
    }
}
