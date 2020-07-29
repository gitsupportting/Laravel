<?php

use App\Models\Policy;
use Illuminate\Support\Carbon;
use Tests\Feature\TestCase;
use Tests\Feature\WorksWithUsers;

class ManagerPolicyTest extends TestCase
{
    use WorksWithUsers;

    public function test_manager_cant_view_other_policies()
    {
        $manager = $this->manager();
        $policy = factory(Policy::class)->create(
            [
                'organization_id' => $manager->managerOrganization()->id++,
            ]
        );

        $this->actingAs($manager);
        $this->expectExceptionMessage('This action is unauthorized');
        $this->get(route('policy.show', $policy));
    }

    public function test_manager_can_view_own_organization_policy()
    {
        $manager = $this->manager();
        $policy = factory(Policy::class)->create(
            [
                'organization_id' => $manager->managerOrganization()->getKey(),
            ]
        );

        $this->actingAs($manager);
        $this->get(route('policy.show', $policy))
            ->assertSeeText($policy->name)
            ->assertSeeText("Created by {$policy->name_on_policy}, Version {$policy->version}")
            ->assertSee($policy->description);
    }

    public function test_manager_can_read_policy()
    {
        $now = now()->setDate(2020, 1, 1)->setTime(1, 1, 1);
        $manager = $this->manager();
        $policy = factory(Policy::class)->create(
            [
                'organization_id' => $manager->managerOrganization()->getKey(),
            ]
        );

        Carbon::setTestNow($now);

        $this->actingAs($manager);
        $this->get(route('policy.read', $policy))->assertRedirect();
        $this->assertDatabaseHas('policy_user', [
            'user_id' => $manager->getKey(),
            'policy_id' => $policy->getKey(),
            'read_at' => $now,
        ]);
    }
}
