<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name'           => $row['nama'],
            'email'          => $row['email'],
            // Tambahkan baris ini untuk mengisi username otomatis dari nama email
            'username'       => explode('@', $row['email'])[0] . rand(100, 999), 
            'role'           => 'siswa',
            'password'       => bcrypt($row['nisn']), 
            'nisn'           => $row['nisn'],
            'kelas'          => $row['kelas'],
            // Sesuaikan dengan nama kolom di database Anda (misal: angkatan atau angkatan_masuk)
            'angkatan'       => $row['angkatan'], 
        ]);
    }
}