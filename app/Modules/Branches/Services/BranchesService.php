<?php

namespace App\Modules\Branches\Services;

use App\Models\Branch;
use Illuminate\Support\Str;

class BranchesService
{
    public function getBranchesForCompany($companyId)
    {
        return Branch::where('company_id', $companyId)->get();
    }

    public function createBranch($companyId, $data)
    {
        $data['company_id'] = $companyId;

        // Map nama cabang dari request ke kolom database
        if (! empty($data['name_branch'])) {
            $data['branch_name'] = $data['name_branch'];
        }

        // Pastikan branch_code selalu terisi untuk memenuhi NOT NULL constraint
        if (empty($data['branch_code'])) {
            do {
                $code = 'BR-' . strtoupper(Str::random(4));
            } while (
                Branch::where('company_id', $companyId)
                    ->where('branch_code', $code)
                    ->exists()
            );

            $data['branch_code'] = $code;
        }

        return Branch::create($data);
    }

    public function updateBranch($branchId, $data)
    {
        $branch = Branch::findOrFail($branchId);
        $branch->update($data);
        return $branch;
    }

    public function deleteBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $branch->delete();
    }

    public function getBranchById($branchId)
    {
        return Branch::findOrFail($branchId);
    }
}
