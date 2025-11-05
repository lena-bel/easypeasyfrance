<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\Steps;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testHydration(): void
    {
        $task = new Task();

        $task->setTitle('OFII Validation');
        $task->setDescription('Validate your VLS-TS visa within 3 months');
        $task->setAppointmentNote('Bring your passport and proof of address');
        $task->setAppointmentLink('https://admin.easy-peasy.fr/appointments');
        $task->setInformationalMessage('This step is mandatory for all new arrivals.');
        $task->setStatus('active');

        $now = new \DateTime();
        $task->setCreatedAt($now);
        $task->setUpdatedAt($now);

        $this->assertSame('OFII Validation', $task->getTitle());
        $this->assertSame('Validate your VLS-TS visa within 3 months', $task->getDescription());
        $this->assertSame('Bring your passport and proof of address', $task->getAppointmentNote());
        $this->assertSame('https://admin.easy-peasy.fr/appointments', $task->getAppointmentLink());
        $this->assertSame('This step is mandatory for all new arrivals.', $task->getInformationalMessage());
        $this->assertSame('active', $task->getStatus());
        $this->assertSame($now, $task->getCreatedAt());
        $this->assertSame($now, $task->getUpdatedAt());
    }

    public function testAddAndRemoveSteps(): void
    {
        $task = new Task();
        $step = new Steps();
        $step->setStep('Create account');

        $task->addStep($step);
        $this->assertCount(1, $task->getSteps());
        $this->assertSame($task, $step->getTaskId());

        $task->removeStep($step);
        $this->assertCount(0, $task->getSteps());
        $this->assertNull($step->getTaskId());
    }
}
