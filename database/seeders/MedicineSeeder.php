<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Medicine::firstOrCreate(['code' => 'ACID_VC']);
        Medicine::firstOrCreate(['code' => 'VCE']);
        Medicine::firstOrCreate(['code' => 'VC_E']);
        Medicine::firstOrCreate(['code' => 'VC_E_FL']);
        Medicine::firstOrCreate(['code' => 'VC_E_P_ND_H9']);
        Medicine::firstOrCreate(['code' => 'VC_E_IB']);
        Medicine::firstOrCreate(['code' => 'VC_E_P_ND_H9']);
        Medicine::firstOrCreate(['code' => 'EC']);
        Medicine::firstOrCreate(['code' => 'F_Cheeni']);
        Medicine::firstOrCreate(['code' => 'MV']);
        Medicine::firstOrCreate(['code' => 'VCEMV']);
        Medicine::firstOrCreate(['code' => 'MV_AM']);
        Medicine::firstOrCreate(['code' => 'FL_LT']);
        Medicine::firstOrCreate(['code' => 'GVO']);
        Medicine::firstOrCreate(['code' => 'RC']);
        Medicine::firstOrCreate(['code' => 'RC_IF']);
        Medicine::firstOrCreate(['code' => 'RC_DX']);
        Medicine::firstOrCreate(['code' => 'COL_IB']);
        Medicine::firstOrCreate(['code' => 'BGN_IBS']);
        Medicine::firstOrCreate(['code' => 'BGN_WRX_COL']);
        Medicine::firstOrCreate(['code' => 'BGN_COLABIX']);
        Medicine::firstOrCreate(['code' => 'BM_EST_LIV']);
        Medicine::firstOrCreate(['code' => 'MB_EST_LIV']);
        Medicine::firstOrCreate(['code' => 'LINCO_VC']);
        Medicine::firstOrCreate(['code' => 'FLUSH_VC']);
        Medicine::firstOrCreate(['code' => 'MB_BLDR_LIV']);
        Medicine::firstOrCreate(['code' => 'YEAST_VC']);
    }
}
