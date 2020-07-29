<?php

namespace App\Http\Requests;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OrganizationStoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $organization = $this->route('organization', optional());
        $manager = optional($organization->manager);

        $rules = [
            'organization.name' => [
                'required',
                Rule::unique(Organization::class, 'name')->ignoreModel($organization)
            ],
            'organization.license_capacity' => 'required|integer|min:0',
            'organization.notes' => 'nullable|string',
            'organization.created_at' => 'nullable|string|date_format:d/m/Y',
            'manager.first_name' => 'required',
            'manager.last_name' => 'required',
            'manager.password' => 'confirmed',
            'manager.email' => [
                'required',
                'email',
                Rule::unique(User::class,'email')->ignoreModel($manager)
            ],
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function organizationData(): array
    {
        $organization = $this->input('organization', []);
        if (empty($organization['notes'])) {
            $organization['notes'] = (string) $organization['notes'];
        }
        if (isset($organization['created_at'])) {
            $organization['created_at'] = Carbon::createFromFormat('d/m/Y', $organization['created_at']);
        }
        return $organization;
    }

    /**
     * @return array
     */
    public function managerData(): array
    {
        $manager = $this->input('manager', []);

        if (!empty($manager['password'])) {
            $manager['password'] = Hash::make($manager['password']);
        } else {
            unset($manager['password']);
        }

        return $manager;
    }
}
