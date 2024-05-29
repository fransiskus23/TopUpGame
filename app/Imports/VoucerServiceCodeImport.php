<?php

namespace App\Imports;

use App\Models\VoucherCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VoucerServiceCodeImport implements ToModel,WithHeadingRow
{
    public $voucher_id;
    public $voucher_service_id;
    public function __construct($voucher_id,$voucher_service_id)
    {
        $this->voucher_id = $voucher_id;
        $this->voucher_service_id = $voucher_service_id;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new VoucherCode([
            "voucher_id" => $this->voucher_id,
            "voucher_service_id" => $this->voucher_service_id,
          "code" => strtolower($row['code']),
        ]);

    }
}
