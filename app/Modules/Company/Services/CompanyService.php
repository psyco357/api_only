<?php

namespace App\Modules\Company\Services;

use App\Models\Company;
use App\Models\User;

class CompanyService
{
    public function getCompaniesForUser(User $user)
    {
        return Company::where('id', $user->company_id)->get();
    }

    public function updateCompanyInfo(User $user, array $data)
    {
        $company = Company::findOrFail($user->company_id);
        $company->update($data);
        return $company;
    }

    public function deleteCompany(User $user): void
    {
        $company = Company::findOrFail($user->company_id);
        $company->delete();
    }
}
