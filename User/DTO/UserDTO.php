<?php

namespace app\User\DTO;

class UserDTO
{
    public function __construct(
        public readonly string  $username,
        public readonly ?string $roles,
        public readonly ?string $role,
        public readonly ?string $name,
        public readonly ?string $secondname,
        public readonly ?string $middlename,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $bday,
        public readonly ?int    $sex,
        public readonly ?int    $vk,
        public readonly ?int    $tl,
        public readonly ?string $utm,
        public readonly ?string $picture,
        public readonly ?string $create_at,
        public readonly ?int    $status,
    )
    {
    }


    // создать DTO из VK
    public static function vk(array $response): ?self
    {
        if (empty($response['id']) or empty($response['screen_name']) or empty($response['first_name'])) return null;

        $username = $response['screen_name'];
        $roles = '[]';
        $role = null;
        $name = $response['first_name'];
        $secondname = null;
        $middlename = null;
        $email = null;
        $phone = null;
        $bday = null;
        $sex = null;
        $vk = (int)$response['id'];
        $tl = null;
        $utm = null;
        $picture = null;

        if (!empty($response['last_name'])) $secondname = $response['last_name'];
        if (!empty($response['photo_400_orig'])) $picture = $response['photo_400_orig'];
        if (!empty($response['sex'])) $sex = (int)$response['sex'];

        $create_at = date('Y-m-d H:i:s');
        $status = 10;

        return new self($username, $roles, $role, $name, $secondname, $middlename, $email, $phone, $bday, $sex, $vk, $tl, $utm, $picture, $create_at, $status);
    }

}
