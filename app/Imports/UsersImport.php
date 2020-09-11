<?php

namespace App\Imports;

// use App\User;
use Illuminate\Support\Facades\Hash;
// // use Maatwebsite\Excel\Concerns\ToModel;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\Importable;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkOffset;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithBatchInserts, WithChunkReading
{
    // use RemembersChunkOffset;

    public function model(array $row)
    {
        // $chunkOffset = $this->getChunkOffset();
        $row = array_map(function($value) {
            return trim($value);
        }, $row);
        
        return new User([
            'name' => $row[0],
            'sigun_code'      => $row[0],
            'user_id'         => $row[1],
            'password'        => Hash::make($row[2]),
            'name'            => $row[3],
            'address'         => $row[4],
            'contact'         => $row[5],
            'representative'  => $row[6],
            'activated'       => $row[7],
            'is_admin'        => $row[8],
            'sequence'        => $row[9],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}


// // use App\Group;
// use Maatwebsite\Excel\Row;
// use Maatwebsite\Excel\Concerns\OnEachRow;
// use Maatwebsite\Excel\Concerns\WithBatchInserts;
// use Maatwebsite\Excel\Concerns\WithChunkReading;
//
//
// class UsersImport implements OnEachRow, WithBatchInserts, WithChunkReading
// {
//     use RemembersChunkOffset;
//
//     public function onRow(Row $row)
//     {
//         $chunkOffset = $this->getChunkOffset();
//
//         $rowIndex = $row->getIndex();
//         $row      = $row->toArray();
//
//         if ($chunkOffset != 1) {
//           User::create([
//               'sigun_code'      => $row[0],
//               'user_id'         => $row[1],
//               'password'        => Hash::make($row[2]),
//               'name'            => $row[3],
//               'address'         => $row[4],
//               'contact'         => $row[5],
//               'representative'  => $row[6],
//               'activated'       => $row[7],
//               'is_admin'        => $row[8],
//               'sequence'        => $row[9],
//           ]);
//         }
//         //
//         // $group = Group::firstOrCreate([
//         //     'name' => $row[1],
//         // ]);
//         //
//         // $group->users()->create([
//         //     'name' => $row[0],
//         // ]);
//     }
//
//     public function batchSize(): int
//     {
//         return 1000;
//     }
//
//     public function chunkSize(): int
//     {
//         return 1000;
//     }
// }


// class UsersImport implements ToModel
// {
//     use Importable;
//
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         return new User([
//             'sigun_code'      => $row[0],
//             'user_id'         => $row[1],
//             'password'        => Hash::make($row[2]),
//             'name'            => $row[3],
//             'address'         => $row[4],
//             'contact'         => $row[5],
//             'representative'  => $row[6],
//             'activated'       => $row[7],
//             'is_admin'        => $row[8],
//             'sequence'        => 10
//         ]);
//     }
// }


// use Maatwebsite\Excel\Concerns\WithHeadingRow;
//
// class UsersImport implements ToCollection, WithHeadingRow
// {
//     use Importable;
//
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $row)
//         {
//           // User::create([
//           //     'sigun_code'      => $row[0],               //$row['시군명']
//           //     'user_id'         => $row[1],               //$row['사용자ID']
//           //     'password'        => Hash::make($row[2]),   //$row['비밀번호']
//           //     'name'            => $row[3],
//           //     'address'         => $row[4],
//           //     'contact'         => $row[5],
//           //     'representative'  => $row[6],
//           //     'activated'       => $row[7],
//           //     'is_admin'        => $row[8],
//           //     'sequence'        => 10
//           // ]);
//           User::create([
//               'sigun_code'      => $row['sigun'],               //$row['시군명']
//               'user_id'         => $row['userid'],               //$row['사용자ID']
//               'password'        => Hash::make($row['password']),   //$row['비밀번호']
//               'name'            => $row['name'],
//               'address'         => $row['address'],
//               'contact'         => $row['contact'],
//               'representative'  => $row['representative'],
//               'activated'       => $row['activated'],
//               'is_admin'        => $row['isadmin'],
//               'sequence'        => $row['sequence'],
//           ]);
//         }
//     }
// }
