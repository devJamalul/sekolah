<?php

use App\Models\User;

dataset('foundation_staff', [
    User::ROLE_ADMIN_YAYASAN => [fn () => $this->adminYayasan],
]);

dataset('school_staff', [
    User::ROLE_ADMIN_SEKOLAH => [fn () => $this->adminSekolah],
    User::ROLE_KEPALA_SEKOLAH => [fn () => $this->kepalaSekolah],
    User::ROLE_BENDAHARA => [fn () => $this->bendahara],
    User::ROLE_TATA_USAHA => [fn () => $this->tataUsaha],
]);

dataset('sempoa_staff', [
    User::ROLE_SUPER_ADMIN => [fn () => $this->superAdmin],
    User::ROLE_OPS_ADMIN => [fn () => $this->opsAdmin],
]);
